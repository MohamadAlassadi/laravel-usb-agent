<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function showDashboard(Request $request)
    {
        return view('dashboard');
    }

    public function showUSBFiles(Request $request)
    {
        return view('usb.files');
    }

    public function logout(Request $request)
    {
        return view('auth.login')->with('logout', true);
    }
}
