<?php

namespace Orq\Laravel\Starter\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class IndexController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('OrqStarter::admin.index', ['siteName' => 'siteamen']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->forget('app_id');
        return redirect('/login');
    }
}
