<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuncCode extends Model
{
    public $timestamps = FALSE;
    protected $fillable = [
        'name',
        'code',
        'is_on',
    ];
}
