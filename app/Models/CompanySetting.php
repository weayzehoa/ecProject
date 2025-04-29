<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin as AdminDB;

class CompanySetting extends Model
{
    protected $fillable = [
        'name',
        'name_en',
        'brand',
        'tax_num',
        'tel',
        'fax',
        'address',
        'address_en',
        'lon',
        'lat',
        'service_tel',
        'service_email',
        'service_time_start',
        'service_time_end',
        'website',
        'url',
        'fb_url',
        'ig_url',
        'tg_url',
        'line_id',
        'line_qrcode',
        'wechat_id',
        'admin_id',
    ];

    public function admin(){
        return $this->belongsTo(AdminDB::class);
    }

}














