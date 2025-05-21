<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SystemSettingService;
use App\Http\Requests\Admin\SystemSettingRequest;

class SystemSettingController extends Controller
{
    protected $systemSettingService;
    protected $funcCode = 'systemSettings';

    public function __construct(SystemSettingService $systemSettingService)
    {
        $this->menuCode = getMenuCode($this->funcCode);
        $this->systemSettingService = $systemSettingService;
    }

    public function index()
    {
        $menuCode = $this->menuCode;
        $systemSetting = $this->systemSettingService->show(1);
        return view('admin.settings.systemSetting', compact(['systemSetting','menuCode']));
    }

    public function update(SystemSettingRequest $request, string $id)
    {
        $this->systemSettingService->update($request->validated(), $id);
        return redirect()->back();
    }
}