<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Http\Requests\Admin\BannerRequest;

class BannerController extends Controller
{
    protected $articleService;
    protected $menuCode = 'M5S6';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
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
        return view('admin.banners.index', compact($compact)); // 自行調整 view
    }

    public function create()
    {
        return view('admin.banners.show');
    }

    public function store(BannerRequest $request)
    {
        dd($request->all());
        $this->articleService->create($request->validated());
        return redirect()->back();
    }

    public function show(string $id)
    {
        $banner = $this->articleService->show($id);
        return view('admin.banners.show', compact('banner'));
    }

    public function edit(string $id)
    {
        $item = $this->articleService->show($id);
        return view('admin.banners.show', compact('item'));
    }

    public function update(BannerRequest $request, string $id)
    {
        dd($request->all());
        $this->articleService->update($request->validated(), $id);
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $this->articleService->delete($id);
        return redirect()->back();
    }
    /*
        啟用或停用
     */
    public function active(Request $request, $id)
    {
        isset($request->is_on) ? $is_on = $request->is_on : $is_on = 0;
        $this->articleService->update(['is_on' => $is_on], $id);
        return redirect()->back();
    }
    /*
        預覽啟用或停用
     */
    public function preview(Request $request, $id)
    {
        isset($request->is_preview) ? $is_preview = $request->is_preview : $is_preview = 0;
        $this->articleService->update(['is_preview' => $is_preview], $id);
        return redirect()->back();
    }
    /*
        向上排序
    */
    public function sortup(Request $request)
    {
        is_numeric($request->id) ? $this->articleService->sort('banner', 'up', $request->id) : '';
        return redirect()->back();
    }
    /*
        向下排序
    */
    public function sortdown(Request $request)
    {
        is_numeric($request->id) ? $this->articleService->sort('banner', 'down', $request->id) : '';
        return redirect()->back();
    }
}