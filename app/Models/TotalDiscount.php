<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalDiscount extends Model
{
    protected $fillable = [
        'amount',
        'discount',
        'start_time',
        'end_time',
        'is_on'
    ];
}