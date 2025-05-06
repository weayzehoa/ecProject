<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageSetting extends Model
{
    protected $fillable = [
        'type',
        'width',
        'height',
        'small_pic',
        'crop_mode',
        'crop_width',
        'crop_height',
    ];
}
