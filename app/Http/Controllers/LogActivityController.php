<?php

namespace App\Http\Controllers;

use App\Repository\LogActivityRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guard("karyawan")->check()) {
            if (!auth()->guard('karyawan')->user()->can('log - log aktivitas')) {
                return view('roles.forbidden');
            }
        }
        else {
            if (!auth()->user()->can('log - log aktivitas')) {
                return view('roles.forbidden');
            }
        }

        try {
            $search = $request->get('q');
            $page_length = $request->has('page_length') ? $request->get('page_length') : 10;
            $page = $request->has('page') ? $request->get('page') : 1;

            $repo = new LogActivityRepository();
            $data = $repo->list($search, $page_length, $page);

            return view('log_activity.index', compact('data'));
        }
        catch (\Exception $e) {
            Alert::error('Terjadi kesalahan', $e->getMessage());
            return back();
        }
    }
}
