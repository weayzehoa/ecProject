<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SubmenuService;
use App\Services\MainmenuService;
use App\Http\Requests\Admin\SubmenuRequest;

class SubmenuController extends Controller
{
    protected $submenuService;
    protected $mainmenuService;
    protected $funcCode = 'mainmenus';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(SubmenuService $submenuService, MainmenuService $mainmenuService)
    {
        $this->menuCode = getMenuCode($this->funcCode);
        $this->submenuService = $submenuService;
        $this->mainmenuService = $mainmenuService;
        $powerActions = config('menuSetting.powerActions');
        $this->poweractions = array_map(function($item) {
            return (object) $item;
        }, $powerActions);
    }

    public function index()
    {
        return redirect()->back();
    }

    public function create()
    {
        return redirect()->back();
    }

    public function store(SubmenuRequest $request)
    {
        $result = $this->submenuService->create($request->validated());
        return redirect()->route('admin.mainmenus.submenu',$result->mainmenu_id);
    }

    public function show(string $id)
    {
        $subMenu = $this->submenuService->show($id);
        $mainMenu = $this->mainmenuService->show($subMenu->mainmenu_id);
        return view('admin.submenus.show', ['menuCode' => $this->menuCode, 'poweractions' => $this->poweractions, 'subMenu' => $subMenu, 'mainMenu' => $mainMenu]);
    }

    public function edit(string $id)
    {
        $mainMenu = $this->mainmenuService->show($id);
        return view('admin.submenus.show', ['menuCode' => $this->menuCode, 'poweractions' => $this->poweractions, 'mainMenu' => $mainMenu]);
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

        public function active(Request $request, $id)
    {
        $is_on = $request->input('is_on', 0);
        $this->submenuService->update(['is_on' => $is_on], $id);
        return redirect()->back();
    }

    public function open(Request $request, $id)
    {
        $open_window = $request->input('open_window', 0);
        $this->submenuService->update(['open_window' => $open_window], $id);
        return redirect()->back();
    }

    public function sortup(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->submenuService->sort('up', $request->id);
        }
        return redirect()->back();
    }

    public function sortdown(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->submenuService->sort('down', $request->id);
        }
        return redirect()->back();
    }
}