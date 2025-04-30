<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\GoogleRecaptchaService;

class LoginController extends Controller
{
    public function __construct()
    {
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
            adminLog('登入成功', $admin);
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => '帳號或密碼錯誤',
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    }
}