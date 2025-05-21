<?php

use App\Services\AdminLogService;
use App\Services\MainmenuService;
use App\Services\SubmenuService;

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

if (!function_exists('flattenMessages')) {
    /**
     * 將巢狀 messages 陣列扁平化為 Laravel 驗證可識別格式
     *
     * 範例輸入：
     * [
     *     'name' => [
     *         'required' => '請輸入名稱',
     *         'max' => '最多 :max 字',
     *     ],
     *     'tags.*' => [
     *         'in' => '標籤的值無效',
     *     ]
     * ]
     *
     * 回傳：
     * [
     *     'name.required' => '請輸入名稱',
     *     'name.max' => '最多 :max 字',
     *     'tags.*.in' => '標籤的值無效',
     * ]
     */
    function flattenMessages(array $messages): array
    {
        return collect($messages)->mapWithKeys(function ($value, $key) {
            if (is_array($value)) {
                return collect($value)->mapWithKeys(function ($msg, $rule) use ($key) {
                    return ["{$key}.{$rule}" => $msg];
                })->all();
            }
            return [$key => $value];
        })->all();
    }
}

if (!function_exists('getMenuCode')) {
    function getMenuCode(string $funcCode): string
    {
        $menuCode = null;
        $where = ['func_code' => $funcCode];

        $submenu = app(SubmenuService::class)->firstWhere($where);
        if(!empty($submenu)){
            $menuCode = $submenu->code;
        }else{
            $mainmenu = app(MainmenuService::class)->firstWhere($where);
            if(!empty($mainmenu)){
                $menuCode = $mainmenu->code;
            }
        }

        return $menuCode;
    }
}