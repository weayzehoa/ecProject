<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\ImageSetting as ImageSettingDB;

class NewsController extends Controller
{
    protected $articleService;
    protected $menuCode = 'M5S7';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
        $this->imageSetting = ImageSettingDB::where('type','news')->first();
    }

    public function index()
    {
        $where = $search = $with = $orderBy = $news = $appends = $compact = [];
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

        $news = $this->articleService->get([['type','news']], $search, $with, [['sort','asc']], $list);
        $compact = array_merge($compact, ['menuCode', 'lists', 'appends', 'news']);
        return view('admin.news.index', compact($compact));
    }

    public function create()
    {
        return view('admin.news.show', ['menuCode' => $this->menuCode, 'imageSetting' => $this->imageSetting]);
    }

    public function store(NewsRequest $request)
    {
        $result = $this->articleService->create($request);
        if (is_string($result)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result);
        }
        return redirect()->route('admin.news.index')->with('success', '新增成功');
    }

    public function show(string $id)
    {
        $new = $this->articleService->show($id);
        return view('admin.news.show', ['menuCode' => $this->menuCode, 'imageSetting' => $this->imageSetting, 'new' => $new]);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(NewsRequest $request, string $id)
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
            $this->articleService->sort('new', 'up', $request->id);
        }
        return redirect()->back();
    }

    public function sortdown(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->articleService->sort('new', 'down', $request->id);
        }
        return redirect()->back();
    }
}
