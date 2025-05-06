<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\ImageSetting as ImageSettingDB;

class BannerController extends Controller
{
    protected $articleService;
    protected $menuCode = 'M5S6';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        $this->imageSetting = ImageSettingDB::where('type','banner')->first();
    }

    public function index()
    {
        $where = $search = $with = $orderBy = $banners = $appends = $compact = [];
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

        $banners = $this->articleService->get([['type','banner']], $search, $with, [['sort','asc']], $list);
        $compact = array_merge($compact, ['menuCode', 'lists', 'appends', 'banners']);
        return view('admin.banners.index', compact($compact));
    }

    public function create()
    {
        return view('admin.banners.show', ['menuCode' => $this->menuCode, 'imageSetting' => $this->imageSetting]);
    }

    public function store(BannerRequest $request)
    {
        $result = $this->articleService->create($request);
        if (is_string($result)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result);
        }
        return redirect()->route('admin.banners.index')->with('success', '新增成功');
    }

    public function show(string $id)
    {
        $banner = $this->articleService->show($id);
        return view('admin.banners.show', ['menuCode' => $this->menuCode, 'imageSetting' => $this->imageSetting, 'banner' => $banner]);
    }

    public function update(BannerRequest $request, string $id)
    {
        $result = $this->articleService->update($request, $id);
        if (is_string($result)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result);
        }
        return redirect()->back()->with('success', '修改成功');
    }

    public function destroy(string $id)
    {
        $this->articleService->delete($id);
        return redirect()->back()->with('success', '刪除成功');
    }

    public function active(Request $request, $id)
    {
        $is_on = $request->input('is_on', 0);
        $this->articleService->onOff(['is_on' => $is_on], $id);
        return redirect()->back();
    }

    public function preview(Request $request, $id)
    {
        $is_preview = $request->input('is_preview', 0);
        $this->articleService->onOff(['is_preview' => $is_preview], $id);
        return redirect()->back();
    }

    public function sortup(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->articleService->sort('banner', 'up', $request->id);
        }
        return redirect()->back();
    }

    public function sortdown(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->articleService->sort('banner', 'down', $request->id);
        }
        return redirect()->back();
    }
}
