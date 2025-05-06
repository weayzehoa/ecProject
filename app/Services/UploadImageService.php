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
    /**
     * 上傳並處理主圖、中圖、小圖（僅儲存原始格式），若有舊圖則一併刪除。
     */
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

            $image = $imageManager->read($file->getRealPath());
            $image = $this->resizeBySetting($image, $type, $imageManager);
            if (!$image) return 'ERROR:圖片尺寸設定錯誤';

            $image->save($originalPath);
            OptimizerChainFactory::create()->optimize($originalPath);

            $setting = $this->getSizeFromSetting($type);
            if (!isset($setting['small_pic']) || $setting['small_pic']) {
                if (!$this->generateVariant($file->getRealPath(), $dir, $filename, $origExt, $type, '_m', 2, $imageManager)) {
                    return 'ERROR:中圖處理失敗';
                }

                if (!$this->generateVariant($file->getRealPath(), $dir, $filename, $origExt, $type, '_s', 4, $imageManager)) {
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

    /**
     * 產生縮圖（中圖或小圖），僅儲存原始格式。
     */
    protected function generateVariant(string $originalPath, string $dir, string $filename, string $ext, string $type, string $suffix, int $scale, ImageManager $imageManager): bool
    {
        try {
            $setting = $this->getSizeFromSetting($type);
            if (!is_array($setting)) {
                Log::warning("縮圖處理失敗（{$suffix}）: 設定不存在");
                Session::flash('error', "縮圖處理失敗（{$suffix}）: 設定不存在");
                return false;
            }

            $width = isset($setting['width']) && is_numeric($setting['width']) && $setting['width'] > 0 ? intdiv((int)$setting['width'], $scale) : null;
            $height = isset($setting['height']) && is_numeric($setting['height']) && $setting['height'] > 0 ? intdiv((int)$setting['height'], $scale) : null;

            if ($width === null && $height === null) {
                Log::warning("縮圖處理失敗（{$suffix}）: width 和 height 均為空");
                Session::flash('error', "縮圖處理失敗（{$suffix}）: 缺少寬高設定");
                return false;
            }

            $image = $imageManager->read($originalPath);
            $image = $this->resizeWithPadding($image, $width, $height, $imageManager);

            $path = "$dir/{$filename}{$suffix}.{$ext}";
            $image->save($path);
            OptimizerChainFactory::create()->optimize($path);

            return true;
        } catch (Exception $e) {
            Log::warning("縮圖處理失敗（{$suffix}）: " . $e->getMessage());
            Session::flash('error', "縮圖處理失敗（{$suffix}）: 內部錯誤");
            return false;
        }
    }

    /**
     * 刪除指定圖檔（原圖、中圖、小圖）之原始格式。
     */
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

    /**
     * 從資料庫中根據 type 取得寬高設定，若無則使用 default 設定。
     */
    protected function getSizeFromSetting(?string $type): ?array
    {
        $searchType = $type ?: 'default';

        $setting = ImageSetting::where('type', $searchType)->first()
            ?? ImageSetting::where('type', 'default')->first();

        return $setting ? [
            'width' => $setting->width,
            'height' => $setting->height,
            'small_pic' => (int) $setting->small_pic, // ← 加入這行
        ] : null;
    }

    /**
     * 根據 type 設定取得寬高，並進行補邊縮放處理。
     */
    protected function resizeBySetting($image, string $type, ImageManager $imageManager)
    {
        $setting = $this->getSizeFromSetting($type);
        if (!$setting) return null;

        $width = isset($setting['width']) && is_numeric($setting['width']) && $setting['width'] > 0 ? (int) $setting['width'] : null;
        $height = isset($setting['height']) && is_numeric($setting['height']) && $setting['height'] > 0 ? (int) $setting['height'] : null;

        if ($width === null && $height === null) return null;

        return $this->resizeWithPadding($image, $width, $height, $imageManager);
    }

    /**
     * 補邊縮放圖片，背景支援透明格式，若不支援則使用黑色背景。
     */
    protected function resizeWithPadding($image, ?int $width, ?int $height, ImageManager $imageManager)
    {
        $resized = $image->scaleDown($width, $height);

        $mime = $image->origin()->mediaType();
        $transparentSupported = in_array($mime, ['image/png', 'image/webp']);
        $background = $transparentSupported ? 'rgba(0,0,0,0)' : '#000000';

        $finalWidth = $width ?? $resized->width();
        $finalHeight = $height ?? $resized->height();

        return $imageManager->create($finalWidth, $finalHeight)->fill($background)->place($resized, 'center');
    }
}