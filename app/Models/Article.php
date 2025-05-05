<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'type',
        'title',
        'description',
        'content',
        'url',
        'img',
        'file',
        'start_time',
        'end_time',
        'is_on',
        'is_preview',
        'sort',
        'admin_id',
    ];

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

    public function imageSetting(){
        return $this->belongsTo(ImageSetting::class,'type','type');
    }
}