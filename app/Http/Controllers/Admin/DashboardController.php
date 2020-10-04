<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        return view('admin.user.index', ['users' => User::get()]);
    }
}
