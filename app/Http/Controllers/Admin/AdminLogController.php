<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdminLogService;
use App\Http\Requests\Admin\AdminLogCreateRequest;
use App\Http\Requests\Admin\AdminLogUpdateRequest;

class AdminLogController extends Controller
{
    protected $adminLogService;
    protected $menuCode = 'M1S4';
    protected $lists = ['15', '30', '50', '100'];
    protected $roles = ['' => '請選擇角色群組', 'develop' => '開發團隊', 'admin' => '管理員', 'staff' => '一般員工'];

    public function __construct(AdminLogService $adminLogService)
    {
        $this->adminLogService = $adminLogService;
    }

    public function index()
    {
        $menuCode = $this->menuCode;
        $lists = $this->lists;
        $roles = $this->roles;
        $adminLogs = $appends = $compact = [];

        foreach (request()->all() as $key => $value) {
            $$key = $value;
            if (isset($$key)) {
                $appends = array_merge($appends, [$key => $value]);
                $compact = array_merge($compact, [$key]);
            }
        }

        if (!isset($list)) {
            $list = 50;
            $compact = array_merge($compact, ['list']);
        }

        $adminLogs = $this->adminLogService->get($list);
        $compact = array_merge($compact, ['appends', 'menuCode', 'lists', 'roles', 'adminLogs']);
        return view('admin.settings.adminLog', compact($compact)); // 自行調整 view
    }

    public function create()
    {
        return redirect()->back();
    }

    public function store(Request $request)
    {
        return redirect()->back();
    }

    public function show(string $id)
    {
        return redirect()->back();
    }

    public function edit(string $id)
    {
        return redirect()->back();
    }

    public function update(Request $request, string $id)
    {
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        return redirect()->back();
    }
}