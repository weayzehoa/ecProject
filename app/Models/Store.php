<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'intro',
        'description',
        'boss',
        'contact',
        'tax_num',
        'tel',
        'fax',
        'address',
        'email',
        'lon',
        'lat',
        'img',
        'service_time_start',
        'service_time_end',
        'url',
        'fb_url',
        'ig_url',
        'line_id',
        'line_qrcode',
        'wechat_id',
        'is_on',
        'is_preview',
        'sort',
    ];
}