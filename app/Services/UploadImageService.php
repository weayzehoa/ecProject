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
    public function upload(UploadedFile $file, string $type = 'default', ?string $oldFilename = null, ?ImageManager $imageManager = null): ?string
    {
        try {
            $imageManager = $imageManager ?: app(ImageManager::class);
            $filename = $type . '_' . time();
            $dir = storage_path("app/public/upload/{$type}");

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0775, true);
            }

            if ($oldFilename) {
                $deleteError = $this->delete($oldFilename, $type);
                if ($deleteError) return $deleteError;
            }

            // Imagick DPI 處理（如有）
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
            $mime = $image->origin()->mediaType();
            $ext = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => $file->getClientOriginalExtension(),
            };

            $originalPath = "$dir/{$filename}.{$ext}";

            $resizedImage = $this->resizeBySetting($image, $type, $imageManager) ?? $image;
            $resizedImage->save($originalPath); // ✅ 正確儲存方式
            OptimizerChainFactory::create()->optimize($originalPath);

            $setting = $this->getSizeFromSetting($type);
            if (!isset($setting['small_pic']) || $setting['small_pic']) {
                if (!$this->generateVariantFromImage($resizedImage, $dir, $filename, $ext, $type, '_m', 2, $imageManager)) {
                    return 'ERROR:中圖處理失敗';
                }
                if (!$this->generateVariantFromImage($resizedImage, $dir, $filename, $ext, $type, '_s', 4, $imageManager)) {
                    return 'ERROR:小圖處理失敗';
                }
            }

            return "$type/{$filename}.{$ext}";
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

            $originalWidth = $baseImage->width();
            $originalHeight = $baseImage->height();

            $targetWidth = isset($setting['width']) && $setting['width'] ? intdiv((int)$setting['width'], $scale) : intdiv($originalWidth, $scale);
            $targetHeight = isset($setting['height']) && $setting['height'] ? intdiv((int)$setting['height'], $scale) : intdiv($originalHeight, $scale);

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
            'small_pic' => (int)$setting->small_pic,
        ] : null;
    }

    protected function resizeBySetting($image, string $type, ImageManager $imageManager)
    {
        $setting = $this->getSizeFromSetting($type);
        if (!$setting) return null;

        $targetWidth = isset($setting['width']) && $setting['width'] > 0 ? (int)$setting['width'] : null;
        $targetHeight = isset($setting['height']) && $setting['height'] > 0 ? (int)$setting['height'] : null;

        if ($targetWidth === null && $targetHeight === null) return null;

        $originalWidth = $image->width();
        $originalHeight = $image->height();

        if (($targetWidth && $originalWidth <= $targetWidth) && ($targetHeight && $originalHeight <= $targetHeight)) {
            return null;
        }

        return $this->resizeWithSingleOrDoubleLimit($image, $targetWidth, $targetHeight, $imageManager);
    }

    protected function resizeWithSingleOrDoubleLimit($image, ?int $targetWidth, ?int $targetHeight, ImageManager $imageManager)
    {
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        $newWidth = $originalWidth;
        $newHeight = $originalHeight;

        // 規則 1：只限制寬
        if ($targetWidth && !$targetHeight) {
            if ($originalWidth > $targetWidth) {
                $scale = $targetWidth / $originalWidth;
                $newWidth = $targetWidth;
                $newHeight = intval($originalHeight * $scale);
            }
            return $image->resize($newWidth, $newHeight);
        }

        // 規則 2：只限制高
        if (!$targetWidth && $targetHeight) {
            if ($originalHeight > $targetHeight) {
                $scale = $targetHeight / $originalHeight;
                $newHeight = $targetHeight;
                $newWidth = intval($originalWidth * $scale);
            }
            return $image->resize($newWidth, $newHeight);
        }

        // 規則 3：寬高皆 null，不處理
        if (!$targetWidth && !$targetHeight) {
            return $image;
        }

        // 規則 4：寬高皆有，補白至滿版
        $scale = min($targetWidth / $originalWidth, $targetHeight / $originalHeight);
        $newWidth = intval($originalWidth * $scale);
        $newHeight = intval($originalHeight * $scale);
        $resized = $image->resize($newWidth, $newHeight);

        // 若尺寸剛好符合，不需補白
        if ($newWidth === $targetWidth && $newHeight === $targetHeight) {
            return $resized;
        }

        // 補白處理
        $mime = $image->origin()->mediaType();
        $transparentSupported = in_array($mime, ['image/png', 'image/webp']);
        $background = $transparentSupported ? 'rgba(0,0,0,0)' : '#000000';

        $canvas = $imageManager->create($targetWidth, $targetHeight)->fill($background);
        return $canvas->place($resized, 'center');
    }

}
