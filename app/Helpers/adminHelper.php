<?php

use App\Services\AdminLogService;

if (! function_exists('adminLog')) {
    /**
     * @param  string                                  $action
     * @param  \Illuminate\Database\Eloquent\Model|array|string $desc
     * @return \App\Models\AdminLog|null
     */
    function adminLog(string $action, $desc = '',  ?int $adminId = null)
    {
        try {
            // 直接把原始 $desc（可能是 Model、也可能是 array）丟進去
            return app(AdminLogService::class)->log($action, $desc, $adminId);
        } catch (\Throwable $e) {
            \Log::error('Admin log failed: '.$e->getMessage());
            return null;
        }
    }
}