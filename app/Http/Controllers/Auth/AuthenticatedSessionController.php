<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\KaryawanModel;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class AuthenticatedSessionController extends Controller
{
    
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->input_type)
                ->orWhere('username', $request->input_type)
                ->first();

            $karyawan = KaryawanModel::where('nip', $request->input_type)
                ->first();

            if ($user || $karyawan) {
                if (($user && Hash::check($request->password, $user->password)) || ($karyawan && Hash::check($request->password, $karyawan->password))) {
                    $checkSession = DB::table('sessions')
                        ->where('user_id', $user->id ?? $karyawan->nip)
                        ->count();
                    if($checkSession > 0){
                        Alert::warning('Peringatan', 'Akun sedang digunakan di perangkat lain');
                        return redirect()->back();
                    }
                    if (Auth::guard('karyawan')->attempt(['nip' => $request->input_type, 'password' => $request->password])) {
                        // $request->authenticate();
                        $request->session()->regenerate();
    
                        return redirect()->intended(RouteServiceProvider::HOME);
                    } else {
                        if ($user->first_login) {
                            $request->authenticate();
                            $request->session()->regenerate();
    
                            return redirect()->route('password.reset');
                        } else {
                            $request->authenticate();
                            $request->session()->regenerate();
    
                            return redirect()->intended(RouteServiceProvider::HOME);
                        }
                    }
                }
                else {
                    Alert::warning('Peringatan', 'Password yang Anda masukkan salah');
                    return back();
                }
            } else {
                Alert::warning('Peringatan', 'Akun tidak ditemukan');
                return back();
            }
        } catch (\Exception $e) {
            Alert::warning('Peringatan', $e->getMessage());
            return back();
        }
    }


    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
