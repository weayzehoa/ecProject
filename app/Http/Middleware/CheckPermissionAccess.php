<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mainmenu;

class CheckPermissionAccess
{
    public function handle(Request $request, Closure $next, string $funcCode, string $actionCode = '')
    {
        $user = Auth::guard('admin')->user();
        if (!$user) {
            return redirect()->route('admin.login');
        }

        $role = $user->role;

        // ✅ develop 角色完全繞過權限檢查
        if ($role === 'develop') {
            return $next($request);
        }

        $permissions = explode(',', $user->permissions ?? '');

        $submenu = Mainmenu::with('submenu')
            ->get()
            ->flatMap(fn ($main) => $main->submenu)
            ->first(fn ($sub) => $sub->func_code === $funcCode);

        if (!$submenu) {
            adminLog('拒絕訪問', '功能代碼不存在：' . $funcCode . '，URL：' . $request->fullUrl());
            return $this->deny($request, '找不到功能設定');
        }

        $allowRoles = explode(',', $submenu->allow_roles ?? '');
        if (!empty($allowRoles[0]) && !in_array($role, $allowRoles)) {
            adminLog('拒絕訪問', "角色 [{$role}] 無法存取：{$funcCode}，URL：" . $request->fullUrl());
            return $this->deny($request, '角色無權限存取');
        }

        $codePrefix = $submenu->code;

        if ($actionCode) {
            $hasAction = in_array($codePrefix . $actionCode, $permissions);

            if (!$hasAction) {
                adminLog('拒絕訪問', "缺少權限碼：{$codePrefix}{$actionCode}，URL：" . $request->fullUrl());
                return $this->deny($request, '您沒有操作此功能的權限');
            }
        } else {
            if (!in_array($codePrefix, $permissions)) {
                adminLog('拒絕訪問', "缺少基本權限碼：{$codePrefix}，URL：" . $request->fullUrl());
                return $this->deny($request, '您沒有此功能的存取權限');
            }
        }

        return $next($request);
    }

    protected function deny(Request $request, string $message)
    {
        return $request->isMethod('get')
            ? redirect()->route('admin.dashboard')->with('error', $message)
            : response($message, 403);
    }
}
