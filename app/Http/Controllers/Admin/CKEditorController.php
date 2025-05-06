<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CKEditorRequest;
use Illuminate\Http\Request;
use App\Services\UploadImageService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use File;

class CKEditorController extends Controller
{
    protected UploadImageService $uploadImageService;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;
    }

    public function upload(CKEditorRequest $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            // ✅ 使用 service 上傳圖片，並指定存放類型為 'ckeditor'
            $result = $this->uploadImageService->upload($file, 'ckeditor');

            if (str_starts_with($result, 'ERROR')) {
                return response()->json([
                    'uploaded' => 0,
                    'error' => ['message' => $result],
                ]);
            }

            // ✅ 回傳完整圖片網址
            $url = Storage::url("upload/{$result}"); // 因 uploadImageService 回傳的為 ckeditor/xxx.jpg

            return response()->json([
                'uploaded' => 1,
                'fileName' => basename($result),
                'url'      => $url,
            ]);
        }

        return response()->json([
            'uploaded' => 0,
            'error' => ['message' => '沒有上傳檔案'],
        ]);
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
