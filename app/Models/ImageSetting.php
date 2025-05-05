<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageSetting extends Model
{
    protected $fillable = [
        'type',
        'width',
        'height',
    ];
}
