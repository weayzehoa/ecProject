<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminLog as AdminLogDB;

class DashboardController extends Controller
{
    public function index()
    {
//         \DB::statement("INSERT INTO admin_logs (admin_id, action, description, ip, created_at, updated_at)
// VALUES (1, '直接插入 JSON 測試', '{\"name\":\"開發團隊\"}', '127.0.0.1', NOW(), NOW())");

        $log = AdminLogDB::orderBy('id','desc')->first();
        dd($log);


        return view('admin.dashboard');
    }
    public function testing()
    {
        return view('admin.testing');
    }
}
