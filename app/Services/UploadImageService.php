<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Exception;
use App\Models\ImageSetting;

class UploadImageService
{
    public function upload(UploadedFile $file, string $type = 'default', ?string $oldFilename = null, ?ImageManager $imageManager = null): string|null
    {
        try {
            $imageManager = $imageManager ?: app(ImageManager::class);
            $origExt = $file->getClientOriginalExtension();
            $filename = $type . '_' . time();
            $dir = storage_path("app/public/upload/{$type}");

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0775, true);
            }

            if ($oldFilename) {
                $deleteError = $this->delete($oldFilename, $type);
                if ($deleteError) return $deleteError;
            }

            $originalPath = "$dir/{$filename}.{$origExt}";

            // 檢查並強制將 DPI 降為 100（使用 imagick）
            if (extension_loaded('imagick')) {
                try {
                    $imagick = new \Imagick($file->getRealPath());
                    $dpi = $imagick->getImageResolution();

                    if (($dpi['x'] ?? 0) > 100 || ($dpi['y'] ?? 0) > 100) {
                        $imagick->setImageResolution(100, 100);
                        $imagick->resampleImage(100, 100, \Imagick::FILTER_UNDEFINED, 1);
                        $imagick->setImageUnits(\Imagick::RESOLUTION_PIXELSPERINCH);
                        $imagick->writeImage($file->getRealPath());
                    }
                } catch (\Exception $e) {
                    Log::warning('Imagick DPI 處理失敗: ' . $e->getMessage());
                }
            }

            $image = $imageManager->read($file->getRealPath());

            $resizedImage = $this->resizeBySetting($image, $type, $imageManager);
            if (!$resizedImage) {
                $resizedImage = $image; // 尺寸不大於設定時使用原圖
            }

            $resizedImage->save($originalPath);
            OptimizerChainFactory::create()->optimize($originalPath);

            $setting = $this->getSizeFromSetting($type);
            if (!isset($setting['small_pic']) || $setting['small_pic']) {
                if (!$this->generateVariantFromImage($resizedImage, $dir, $filename, $origExt, $type, '_m', 2, $imageManager)) {
                    return 'ERROR:中圖處理失敗';
                }
                if (!$this->generateVariantFromImage($resizedImage, $dir, $filename, $origExt, $type, '_s', 4, $imageManager)) {
                    return 'ERROR:小圖處理失敗';
                }
            }

            return "$type/{$filename}.{$origExt}";
        } catch (Exception $e) {
            Log::error('圖片上傳失敗: ' . $e->getMessage());
            Session::flash('error', '圖片上傳處理失敗');
            return 'ERROR:圖片上傳處理失敗';
        }
    }

    protected function generateVariantFromImage($baseImage, string $dir, string $filename, string $ext, string $type, string $suffix, int $scale, ImageManager $imageManager): bool
    {
        try {
            $setting = $this->getSizeFromSetting($type);
            if (!is_array($setting)) return false;

            $targetWidth = isset($setting['width']) ? intdiv((int)$setting['width'], $scale) : null;
            $targetHeight = isset($setting['height']) ? intdiv((int)$setting['height'], $scale) : null;
            if ($targetWidth === null && $targetHeight === null) return false;

            $variant = $baseImage->scaleDown($targetWidth, $targetHeight);
            $path = "$dir/{$filename}{$suffix}.{$ext}";
            $variant->save($path);
            OptimizerChainFactory::create()->optimize($path);

            return true;
        } catch (Exception $e) {
            Log::warning("縮圖處理失敗（{$suffix}）: " . $e->getMessage());
            return false;
        }
    }

    public function delete(string $filename, string $type): ?string
    {
        try {
            $dir = storage_path("app/public/upload/{$type}");
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            $base = pathinfo($filename, PATHINFO_FILENAME);

            foreach (['', '_m', '_s'] as $suffix) {
                $path = "$dir/{$base}{$suffix}.{$ext}";
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
        } catch (Exception $e) {
            Log::warning('圖片刪除失敗: ' . $e->getMessage());
            Session::flash('error', '圖片刪除時發生例外錯誤');
            return 'ERROR:圖片刪除失敗';
        }

        return null;
    }

    protected function getSizeFromSetting(?string $type): ?array
    {
        $searchType = $type ?: 'default';
        $setting = ImageSetting::where('type', $searchType)->first()
            ?? ImageSetting::where('type', 'default')->first();

        return $setting ? [
            'width' => $setting->width,
            'height' => $setting->height,
            'small_pic' => (int) $setting->small_pic,
        ] : null;
    }

    protected function resizeBySetting($image, string $type, ImageManager $imageManager)
    {
        $setting = $this->getSizeFromSetting($type);
        if (!$setting) return null;

        $targetWidth = isset($setting['width']) && is_numeric($setting['width']) && $setting['width'] > 0 ? (int) $setting['width'] : null;
        $targetHeight = isset($setting['height']) && is_numeric($setting['height']) && $setting['height'] > 0 ? (int) $setting['height'] : null;

        if ($targetWidth === null && $targetHeight === null) return null;

        $originalWidth = $image->width();
        $originalHeight = $image->height();

        if (($targetWidth && $originalWidth <= $targetWidth) && ($targetHeight && $originalHeight <= $targetHeight)) {
            return null; // 不需要 resize
        }

        return $this->resizeWithSingleOrDoubleLimit($image, $targetWidth, $targetHeight, $imageManager);
    }

    protected function resizeWithSingleOrDoubleLimit($image, ?int $maxWidth, ?int $maxHeight, ImageManager $imageManager)
    {
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;

        if ($maxWidth && $originalWidth > $maxWidth) {
            $scale = $maxWidth / $originalWidth;
            $newWidth = $maxWidth;
            $newHeight = intval($originalHeight * $scale);
        }

        if ($maxHeight && $newHeight > $maxHeight) {
            $scale = $maxHeight / $newHeight;
            $newHeight = $maxHeight;
            $newWidth = intval($newWidth * $scale);
        }

        $resized = $image->resize($newWidth, $newHeight);

        $finalWidth = $maxWidth ?? $resized->width();
        $finalHeight = $maxHeight ?? $resized->height();

        $transparentSupported = in_array($image->mime(), ['image/png', 'image/webp']);
        $background = $transparentSupported ? 'rgba(0,0,0,0)' : '#000000';

        $canvas = $imageManager->create($finalWidth, $finalHeight)->fill($background);
        return $canvas->place($resized, 'center');
    }
}
