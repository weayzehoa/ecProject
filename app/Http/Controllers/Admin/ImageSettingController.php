<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ImageSettingService;
use App\Http\Requests\Admin\ImageSettingRequest;

class ImageSettingController extends Controller
{
    protected $imageSettingService;
    protected $menuCode = 'M6S2';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(ImageSettingService $imageSettingService)
    {
        $this->imageSettingService = $imageSettingService;
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

        $settings = $this->imageSettingService->get($list);

        $compact = array_merge($compact, ['menuCode', 'lists', 'appends', 'settings']);
        return view('admin.settings.imageSetting', compact($compact));
    }

    public function create()
    {
        return redirect()->back();
    }

    public function store(ImageSettingRequest $request)
    {
        $this->imageSettingService->create($request->validated());
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

    public function update(ImageSettingRequest $request, string $id)
    {
        $this->imageSettingService->update($request->validated(), $id);
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $this->imageSettingService->delete($id);
        return redirect()->back();
    }
}