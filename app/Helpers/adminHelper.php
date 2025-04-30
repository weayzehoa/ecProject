<?php

use App\Services\AdminLogService;

if (!function_exists('adminLog')) {
    function adminLog(string $action, string $description = ''): void
    {
        try {
            app(AdminLogService::class)->log($action, $description);
        } catch (\Throwable $e) {
            \Log::error('Admin log failed: ' . $e->getMessage());
        }
    }
}