<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $fillable = [
        'admin_id',
        'action',
        'description',
        'ip'
    ];

    //將description欄位轉換為array格式
    protected $casts = ['description' => 'array'];

    public function getDescriptionAttribute($value)
    {
        return is_array($value) ? $value : json_decode($value, true) ?? $value;
    }

}
