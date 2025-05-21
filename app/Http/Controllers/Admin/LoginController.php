<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\GoogleRecaptchaService;
use App\Services\AdminService;
use Illuminate\Support\Facades\Password;
use Hash;
use Str;

class LoginController extends Controller
{
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
        //改走cloudflare需抓x-forwareded-for
        if(!empty(request()->header('x-forwarded-for'))){
            $this->loginIp = request()->header('x-forwarded-for');
        }else{
            $this->loginIp = request()->ip();
        }
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function showForgetForm()
    {
        return view('admin.forgetPass');
    }

    public function showRestForm(string $token)
    {
        return view('admin.resetPass', ['token' => $token]);
    }

    public function login(Request $request, GoogleRecaptchaService $recaptcha)
    {
        $result = $recaptcha->verifyRequest($request);

        if ($result !== true) {
            return back()->withErrors(['recaptcha' => $result]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $admin = Auth::guard('admin')->user();
            $admin->update([
                'last_login_time' => now(),
                'last_login_ip' => $this->loginIp,
            ]);
            adminLog('登入成功', '管理者 '.$admin->name.' 已登入。');
            return redirect()->intended(route('admin.dashboard'));
        }else{
            adminLog('登入失敗', $credentials);
        }

        return back()->withErrors([
            'email' => '帳號或密碼錯誤',
        ]);
    }

    public function sendForgetMail(Request $request, GoogleRecaptchaService $recaptcha)
    {
        $result = $recaptcha->verifyRequest($request);
        if ($result !== true) {
            return back()->withErrors(['recaptcha' => $result]);
        }

        if(!empty($request->email)){
            $admin = $this->adminService->get(null, [], [['email',$request->email]],[] , [], true);

            if (!empty($admin)) {
                $status = Password::broker('admins')->sendResetLink(
                    $request->only('email')
                );
                adminLog('寄送重設密碼信件', '管理者 '.$admin->name.' 寄送重設密碼信件。', $admin->id);

                return $status === Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withErrors(['email' => __($status)]);
            }else{
                adminLog('重設密碼失敗', $request->email.' 找不到管理者');
            }
        }

        return back()->withErrors(['email' => '請輸入正確的管理者電子郵件。']);
    }

    public function resetPass(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                adminLog('重設密碼成功', '管理者 '.$admin->name.' 重設密碼完成。', $admin->id);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout()
    {
        $admin = Auth::guard('admin')->user();
        Auth::guard('admin')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        adminLog('登出成功', '管理者 '.$admin->name.' 已登出。', $admin->id);
        return redirect()->route('login');
    }
}