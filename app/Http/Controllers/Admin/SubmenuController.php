<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SubmenuService;
use App\Http\Requests\Admin\SubmenuRequest;

class SubmenuController extends Controller
{
    protected $submenuService;

    public function __construct(SubmenuService $submenuService)
    {
        $this->submenuService = $submenuService;
    }

    public function index()
    {
        $items = $appends = $compact = [];

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

        $items = $this->submenuService->get($list);

        $compact = array_merge($compact, ['appends', 'items']);
        return view('admin.index', compact($compact)); // 自行調整 view
    }

    public function create()
    {
        return view('admin.create'); // 自行調整 view
    }

    public function store(SubmenuRequest $request)
    {
        $this->submenuService->create($request->validated());
        return redirect()->back();
    }

    public function show(string $id)
    {        return '還沒開發';
        $item = $this->submenuService->show($id);
        return view('admin.show', compact('item')); // 自行調整 view
    }

    public function edit(string $id)
    {
        $item = $this->submenuService->show($id);
        return view('admin.edit', compact('item')); // 自行調整 view
    }

    public function update(SubmenuRequest $request, string $id)
    {
        $this->submenuService->update($request->validated(), $id);
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $this->submenuService->delete($id);
        return redirect()->back();
    }
}