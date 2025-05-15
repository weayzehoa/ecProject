<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MainmenuService;
use App\Services\SubmenuService;
use App\Http\Requests\Admin\MainmenuRequest;

class MainmenuController extends Controller
{
    protected $mainmenuService;
    protected $submenuService;
    protected $menuCode = 'M6S1';
    protected $lists = ['15', '30', '50', '100'];

    public function __construct(MainmenuService $mainmenuService, SubmenuService $submenuService)
    {
        $this->mainmenuService = $mainmenuService;
        $this->submenuService = $submenuService;
        $powerActions = config('menuSetting.powerActions');
        $this->poweractions = array_map(function($item) {
            return (object) $item;
        }, $powerActions);
    }

    public function index()
    {
        $mainMenus = $appends = $compact = [];
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

        $mainMenus = $this->mainmenuService->get($list);

        $compact = array_merge($compact, ['menuCode', 'lists', 'appends', 'mainMenus']);
        return view('admin.mainmenus.index', compact($compact));
    }

    public function create()
    {
        return view('admin.mainmenus.show', ['menuCode' => $this->menuCode, 'poweractions' => $this->poweractions]);
    }

    public function store(MainmenuRequest $request)
    {
        $this->mainmenuService->create($request->validated());
        return redirect()->back();
    }

    public function show(string $id)
    {
        $mainMenu = $this->mainmenuService->show($id);
        return view('admin.mainmenus.show', ['menuCode' => $this->menuCode, 'poweractions' => $this->poweractions, 'mainMenu' => $mainMenu]);
    }

    public function edit(string $id)
    {
        return redirect()->back();
    }

    public function update(MainmenuRequest $request, string $id)
    {
        $this->mainmenuService->update($id, $request->validated());
        return redirect()->back();
    }

    public function destroy(string $id)
    {
        return redirect()->back();
    }

    public function active(Request $request, $id)
    {
        $is_on = $request->input('is_on', 0);
        $this->mainmenuService->update($id, ['is_on' => $is_on]);
        return redirect()->back();
    }

    public function open(Request $request, $id)
    {
        $open_window = $request->input('open_window', 0);
        $this->mainmenuService->update($id, ['open_window' => $open_window]);
        return redirect()->back();
    }

    public function sortup(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->mainmenuService->sort('up', $request->id);
        }
        return redirect()->back();
    }

    public function sortdown(Request $request)
    {
        if (is_numeric($request->id)) {
            $this->mainmenuService->sort('down', $request->id);
        }
        return redirect()->back();
    }

        public function submenu($id)
    {
        $perPage = null; $with = $search = []; $where = [['mainmenu_id',$id]]; $orderBy = [['sort','asc']];
        $subMenus = $this->submenuService->get($perPage, $with, $where, $orderBy, $search);

        dd($subMenus);
        return view('admin.submenus.index', ['menuCode' => $this->menuCode, 'subMenus' => $subMenus]);

    }
}