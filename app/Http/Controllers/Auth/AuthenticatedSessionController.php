<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LogActivity;
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

    public function index(){
        if (!auth()->user()->hasRole('admin')) {
            return view('roles.forbidden');
        }

        return view('auth.session.index');
    }

    public function store(LoginRequest $request)
    {
        DB::beginTransaction();
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
                        DB::commit();
                        Alert::warning('Peringatan', 'Akun sedang digunakan di perangkat lain');
                        return redirect()->back();
                    }
                    if (Auth::guard('karyawan')->attempt(['nip' => $request->input_type, 'password' => $request->password])) {
                        $request->session()->regenerate();

                        // Record to log activity
                        $name = $karyawan->nama_karyawan;
                        $activity = "Pengguna $name melakukan login.";
                        LogActivity::create($activity);

                        DB::commit();

                        return redirect()->intended(RouteServiceProvider::HOME);
                    } else {
                        $request->authenticate();
                        $request->session()->regenerate();

                        // Record to log activity
                        $name = $user->name;
                        $activity = "Pengguna $name melakukan login.";
                        LogActivity::create($activity);

                        DB::commit();

                        if ($user->first_login) {
                            return redirect()->route('password.reset');
                        } else {
                            return redirect()->intended(RouteServiceProvider::HOME);
                        }
                    }
                }
                else {
                    DB::commit();
                    Alert::warning('Peringatan', 'Password yang Anda masukkan salah');
                    return back();
                }
            } else {
                DB::commit();
                Alert::warning('Peringatan', 'Akun tidak ditemukan');
                return back();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::warning('Peringatan', $e->getMessage());
            return back();
        }
    }


    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $name = Auth::guard('karyawan')->check() ? auth()->guard('karyawan')->user()->nama_karyawan : auth()->user()->name;
            // Record to log activity
            $activity = "Pengguna $name melakukan logout.";
            LogActivity::create($activity);

            // Check guard
            if (Auth::guard('karyawan')->check())
                Auth::guard('karyawan')->logout();
            else
                Auth::guard('web')->logout();


            $request->session()->invalidate();

            $request->session()->regenerateToken();

            // Check if logout was successful
            if (Auth::guard('web')->check() || Auth::guard('karyawan')->check()) {
                // Logout failed, return with error message
                DB::rollBack();
                Alert::warning('Peringatan', 'Logout gagal, harap coba lagi');
                return redirect()->back();
            }

            // Logout successful, return with success message and redirect
            DB::commit();
            return redirect('/');
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
