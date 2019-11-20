<?php

namespace Orq\Laravel\Starter\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Orq\Laravel\Starter\Model\Admin;
use Orq\Laravel\Starter\Service\AdminService;
use Orq\Laravel\Starter\IllegalArgumentException;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('fetchData')) {
            try {
                $adminService = resolve(AdminService::class);
                $admins = $adminService->findAll();
                return response()->json(['code'=>0, 'msg'=>'success', 'count'=>count($admins), 'data'=>$admins]);
            } catch (IllegalArgumentException $e) {
                Log::error($e->getMessage());
            }
        }

        return view('OrqStarter::admin.admin.index');
    }

    public function new()
    {
        return view('OrqStarter::admin.admin.form');
    }

    public function save(Request $request)
    {
        try {
            $adminService = resolve(AdminService::class);
            $adminService->createNew($request->all());
            return response()->json(['code'=>0, 'status'=>'success', 'data'=>[]]);
        } catch (IllegalArgumentException $e) {
            return response()->json(['code'=>1,'status'=>'fail', 'msg'=>$e->getMessage()]);
        }
    }

    public function edit(Request $request)
    {
        $admin = Admin::find((int)$request->input('admin_id'));
        return view('OrqStarter::admin.admin.form', ['admin'=>$admin]);
    }

    public function update(Request $request)
    {
        try {
            $adminService = resolve(AdminService::class);
            $adminService->update($request->all());
            return response()->json(['code'=>0, 'status'=>'success', 'data'=>[]]);
        } catch (IllegalArgumentException $e) {
            return response()->json(['code'=>1,'status'=>'fail', 'msg'=>$e->getMessage()]);
        }
    }

    public function deactivate(Request $request)
    {
        try {
            $adminService = resolve(AdminService::class);
            $adminService->deactivateAdmin((int)$request->input('admin_id'));
            return response()->json(['code'=>0, 'status'=>'success', 'data'=>[]]);
        } catch (IllegalArgumentException $e) {
            return response()->json(['code'=>1,'status'=>'fail', 'msg'=>$e->getMessage()]);
        }
    }

    public function restore(Request $request)
    {
        try {
            $adminService = resolve(AdminService::class);
            $adminService->activateAdmin((int)$request->input('admin_id'));
            return response()->json(['code'=>0, 'status'=>'success', 'data'=>[]]);
        } catch (IllegalArgumentException $e) {
            return response()->json(['code'=>1,'status'=>'fail', 'msg'=>$e->getMessage()]);
        }
    }
}
