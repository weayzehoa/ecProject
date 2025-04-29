<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Submenu as SubmenuDB;

class Mainmenu extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'code',
        'icon',
        'name',
        'power_action',
        'allow_roles',
        'func_code',
        'url',
        'url_type',
        'open_window',
        'is_on',
        'sort',
    ];

    //關聯submenu
    public function submenu(){
        return $this->hasMany(SubmenuDB::class)->where('is_on',1)->orderBy('sort','asc');
    }
}
