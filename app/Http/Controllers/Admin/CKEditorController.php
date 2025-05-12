<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CKEditorRequest;
use Illuminate\Http\Request;
use App\Services\UploadImageService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use File;

use Illuminate\Validation\ValidationException;

class CKEditorController extends Controller
{
    protected UploadImageService $uploadImageService;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;
    }

    public function upload(CKEditorRequest $request)
    {
        try {
            if (!$request->hasFile('upload')) {
                return response()->json([
                    'uploaded' => 0,
                    'error' => ['message' => '沒有選擇檔案或檔案無效'],
                ]);
            }

            $file = $request->file('upload');

            // ✅ 正常處理
            $result = $this->uploadImageService->upload($file, 'ckeditor');

            if (str_starts_with($result, 'ERROR')) {
                return response()->json([
                    'uploaded' => 0,
                    'error' => ['message' => $result],
                ]);
            }

            $url = Storage::url("upload/{$result}");

            return response()->json([
                'uploaded' => 1,
                'fileName' => basename($result),
                'url' => $url,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'uploaded' => 0,
                'error' => ['message' => $e->validator->errors()->first('upload')],
            ], 200);
        } catch (\Throwable $e) {
            \Log::error('CKEditor 上傳錯誤：' . $e->getMessage());

            return response()->json([
                'uploaded' => 0,
                'error' => ['message' => '伺服器錯誤，請稍後再試或聯絡管理員'],
            ]);
        }
    }

    public function delete(Request $request)
    {
        $url = $request->input('url');

        // 僅允許刪除 /storage/upload/ckeditor/ 下的圖片
        $base = '/storage/upload/ckeditor/';
        if (str_contains($url, $base)) {
            // 解析出 /upload/ckeditor/xxx.jpg 相對路徑
            $relativePath = parse_url($url, PHP_URL_PATH); // 拿掉 http(s)://domain
            $relativePath = ltrim(str_replace('/storage/', '', $relativePath), '/');

            $filePath = storage_path('app/public/' . $relativePath);

            if (File::exists($filePath)) {
                File::delete($filePath);
                return response()->json(['deleted' => true]);
            }
        }

        return response()->json(['deleted' => false]);
    }
}
