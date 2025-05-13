<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StoreService;
use App\Http\Requests\Admin\StoreRequest;
use App\Models\ImageSetting as ImageSettingDB;

class StoreController extends Controller
{
    protected $storeService;
    protected $menuCode = 'M5S9';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
        $this->imageSetting = ImageSettingDB::where('type','store')->first();
    }

    public function index()
    {
        $stores = $appends = $compact = [];
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

        $stores = $this->storeService->get($list);

        $compact = array_merge($compact, ['menuCode', 'lists', 'appends', 'stores']);
        return view('admin.stores.index', compact($compact)); // 自行調整 view
    }

    public function create()
    {
        return view('admin.stores.show', ['menuCode' => $this->menuCode, 'imageSetting' => $this->imageSetting]);
    }

    public function store(StoreRequest $request)
    {
        $result = $this->storeService->create($request);
        if (is_string($result)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result);
        }
        return redirect()->route('admin.stores.index')->with('success', '新增成功');
    }

    public function show(string $id)
    {
        $store = $this->storeService->show($id);
        return view('admin.stores.show', ['menuCode' => $this->menuCode, 'imageSetting' => $this->imageSetting, 'store' => $store]);

    }

    public function update(StoreRequest $request, string $id)
    {
        $result = $this->storeService->update($request, $id);
        if (is_string($result)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result);
        }
        return redirect()->back()->with('success', '修改成功');
    }

    public function destroy(string $id)
    {
        $this->storeService->delete($id);
        return redirect()->back()->with('success', '刪除成功');
    }

    public function active(Request $request, $id)
    {
        $is_on = $request->input('is_on', 0);
        $this->storeService->onOff(['is_on' => $is_on], $id);
        return redirect()->back();
    }

    public function preview(Request $request, $id)
    {
        $is_preview = $request->input('is_preview', 0);
        $this->storeService->onOff(['is_preview' => $is_preview], $id);
        return redirect()->back();
    }

    public function sortup(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->storeService->sort('up', $request->id);
        }
        return redirect()->back();
    }

    public function sortdown(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->storeService->sort('down', $request->id);
        }
        return redirect()->back();
    }

    public function delimg(Request $request, $id)
    {
        if (is_numeric($id)) {
            $this->storeService->delimg($id);
        }
        return redirect()->back();
    }
}