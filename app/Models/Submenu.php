<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Mainmenu as MainmenuDB;

class Submenu extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'mainmenu_id',
        'code',
        'fa5icon',
        'name',
        'url',
        'url_type',
        'open_window',
        'is_on',
        'sort',
        'power_action',
        'func_code',
    ];

    public function mainmenu(){
        return $this->belongsTo(MainmenuDB::class);
    }
}
