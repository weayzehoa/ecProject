<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Http\Requests\Admin\AdminCreateRequest;
use App\Http\Requests\Admin\AdminUpdateRequest;
use Hash;
use Validator;

class AdminController extends Controller
{
    protected $adminService;
    protected $menuCode = 'M1S3';
    protected $roles = ['' => '請選擇角色群組', 'develop' => '開發團隊', 'admin' => '管理員', 'staff' => '一般員工'];
    protected $status = ['' => '請選擇狀態', '0' => '停權中', '1' => '啟用中'];
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function index()
    {
        $menuCode = $this->menuCode;
        $lists = $this->lists;
        $roles = $this->roles;
        $status = $this->status;
        $admins = $appends = $compact = [];

        foreach (request()->all() as $key => $value) {
            $$key = $value;
                if (isset($$key)) {
                    $appends = array_merge($appends, [$key => $value]);
                    $compact = array_merge($compact, [$key]);
                }
        }

        if (!isset($list)) {
            $list = $lists[0];
            $compact = array_merge($compact, ['list']);
        }

        $admins = $this->adminService->get($list);

        $compact = array_merge($compact, ['menuCode','appends', 'lists', 'roles', 'status', 'admins']);
        return view('admin.admins.index', compact($compact)); // 自行調整 view
    }

    public function create()
    {
        return view('admin.admins.show', ['menuCode' => $this->menuCode, 'roles' => $this->roles]);
    }

    public function store(AdminCreateRequest $request)
    {
        $this->adminService->create($request->validated());
        return redirect()->route('admin.admins.index');
    }

    public function show(string $id)
    {
        $admin = $this->adminService->show($id);
        return view('admin.admins.show', ['menuCode' => $this->menuCode, 'roles' => $this->roles, 'admin' => $admin]);
    }

    public function update(AdminUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        $this->adminService->update($validated, $id);

        return redirect()->back()->with('success', '管理員更新成功');
    }

    public function destroy(string $id)
    {
        $this->adminService->delete($id);
        return redirect()->back();
    }

    public function active(Request $request)
    {
        isset($request->is_on) ? $is_on = $request->is_on : $is_on = 0;
        $this->adminService->update(['is_on' => $is_on], $request->id);
        return redirect()->back();
    }
}