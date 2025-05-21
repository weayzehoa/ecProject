<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ShippingFeeService;
use App\Services\SystemSettingService;
use App\Http\Requests\Admin\ShippingFeeRequest;

class ShippingFeeController extends Controller
{
    protected $shippingFeeService;
    protected $systemSettingService;
    protected $funcCode = 'shippingFees';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(ShippingFeeService $shippingFeeService, SystemSettingService $systemSettingService)
    {
        $this->menuCode = getMenuCode($this->funcCode);
        $this->shippingFeeService = $shippingFeeService;
        $this->systemSettingService = $systemSettingService;
    }

    public function index()
    {
        $settings = $appends = $compact = [];
        $menuCode = $this->menuCode;
        $lists = $this->lists;
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

        $settings = $this->shippingFeeService->get($list);
        $systemSetting = $this->systemSettingService->show(1);
        $systemSettingMenuCode = getMenuCode('systemSettings');
        $compact = array_merge($compact, ['menuCode', 'lists', 'appends', 'settings','systemSetting','systemSettingMenuCode']);
        return view('admin.settings.shippingFee', compact($compact));
    }

    public function create()
    {
        return redirect()->back();
    }

    public function store(ShippingFeeRequest $request)
    {
        $this->shippingFeeService->create($request->validated());
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

    public function update(ShippingFeeRequest $request, string $id)
    {
        $this->shippingFeeService->update($request->validated(), $id);
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $this->shippingFeeService->delete($id);
        return redirect()->back();
    }
}