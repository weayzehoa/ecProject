<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'shippingFees',
        'productPromos',
        'TotalDiscounts',
    ];
}
