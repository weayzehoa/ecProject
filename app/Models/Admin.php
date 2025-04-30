<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable,SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
        'is_on',
        'permissions',
        'is_active',
        'is_lock',
        'tel',
        'mobile',
        'otp_code',
        'otp_time',
        'off_time',
        'sms_vendor',
        '2fa_secret',
        'verify_mode',
        'last_login_time',
        'last_login_ip',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'develop' => '開發團隊',
            'admin'   => '管理員',
            'staff'   => '一般員工',
            default   => '未知角色',
        };
    }
}
