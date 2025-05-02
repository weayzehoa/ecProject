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
        return view('admin.create'); // 自行調整 view
    }

    public function store(AdminLogCreateRequest $request)
    {
        $this->adminLogService->create($request->validated());
        return redirect()->back();
    }

    public function show(string $id)
    {
        $item = $this->adminLogService->show($id);
        return view('admin.show', compact('item')); // 自行調整 view
    }

    public function edit(string $id)
    {
        $item = $this->adminLogService->show($id);
        return view('admin.edit', compact('item')); // 自行調整 view
    }

    public function update(AdminLogUpdateRequest $request, string $id)
    {
        $this->adminLogService->update($request->validated(), $id);
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $this->adminLogService->delete($id);
        return redirect()->back();
    }
}