<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * 將巢狀 messages 陣列轉為平坦陣列，供 Laravel 表單驗證使用。
     */
    public static function flattenMessages(array $messages): array
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
