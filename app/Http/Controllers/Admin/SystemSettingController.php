<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SystemSettingService;
use App\Http\Requests\Admin\SystemSettingRequest;

class SystemSettingController extends Controller
{
    protected $systemSettingService;

    public function __construct(SystemSettingService $systemSettingService)
    {
        $this->systemSettingService = $systemSettingService;
    }

    public function index()
    {
        $menuCode = 'M1S2';
        $systemSetting = $this->systemSettingService->show(1);
        return view('admin.settings.systemSetting', compact(['systemSetting','menuCode']));
    }

    public function update(SystemSettingRequest $request, string $id)
    {
        $this->systemSettingService->update($request->validated(), $id);
        return redirect()->back();
    }
}