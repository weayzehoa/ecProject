<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TotalDiscountService;
use App\Services\SystemSettingService;
use App\Http\Requests\Admin\TotalDiscountRequest;

class TotalDiscountController extends Controller
{
    protected $totalDiscountService;
    protected $systemSettingService;
    protected $menuCode = 'M6S4';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(TotalDiscountService $totalDiscountService, SystemSettingService $systemSettingService)
    {
        $this->totalDiscountService = $totalDiscountService;
                $this->systemSettingService = $systemSettingService;
    }

    public function index()
    {
        $items = $appends = $compact = [];
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

        $settings = $this->totalDiscountService->get($list);
        $systemSetting = $this->systemSettingService->show(1);

        $compact = array_merge($compact, ['menuCode', 'lists', 'appends', 'settings','systemSetting']);
        return view('admin.settings.totalDiscount', compact($compact));
    }

    public function create()
    {
        return view('admin.create'); // 自行調整 view
    }

    public function store(TotalDiscountRequest $request)
    {
        $this->totalDiscountService->create($request->validated());
        return redirect()->back();
    }

    public function show(string $id)
    {
        $item = $this->totalDiscountService->show($id);
        return view('admin.show', compact('item')); // 自行調整 view
    }

    public function edit(string $id)
    {
        $item = $this->totalDiscountService->show($id);
        return view('admin.edit', compact('item')); // 自行調整 view
    }

    public function update(TotalDiscountRequest $request, string $id)
    {
        $this->totalDiscountService->update($request->validated(), $id);
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $this->totalDiscountService->delete($id);
        return redirect()->back();
    }
}