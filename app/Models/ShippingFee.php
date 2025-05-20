<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingFee extends Model
{
    protected $fillable = [
        'name',
        'type',
        'fee',
        'free',
        'discount',
        'is_on',
        'start_time',
        'end_time',
    ];
}