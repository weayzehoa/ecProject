<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CompanySettingService;
use App\Http\Requests\Admin\CompanySettingRequest;

class CompanySettingController extends Controller
{
    private $companySettingService;

    public function __construct(CompanySettingService $companySettingService)
    {
        $this->companySettingService = $companySettingService;
    }

    /**
     * 顯示表單
     */
    public function index()
    {
        $menuCode = 'M1S1';
        $company = $this->companySettingService->show(1);
        return view('admin.settings.companySetting', compact(['company','menuCode']));
    }

    /**
     * 更新公司資料
     */
    public function update(CompanySettingRequest $request, string $id)
    {
        // 通過驗證後的資料會自動回傳
        $data = $request->validated();
    
        // 增加更新人員欄位
        $data['admin_id'] = auth()->user()->id;
    
        // 更新公司資料
        $this->companySettingService->update($id, $data);
    
        return redirect()
            ->route('admin.companySettings.index')
            ->with('success', '公司資料已更新');
    }
}
