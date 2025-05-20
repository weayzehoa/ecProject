<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\AdminResetPasswordNotification;

class Admin extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable,SoftDeletes,CanResetPassword;
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

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }
}
