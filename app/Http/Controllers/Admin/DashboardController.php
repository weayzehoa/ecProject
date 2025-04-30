<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminLog as AdminLogDB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }
    public function testing()
    {
        return view('admin.testing');
    }
}
