<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class AuthenticatedSessionController extends Controller
{
    
    public function create(): View
    {
        return view('login');
    }

    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // } 

    public function store(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->input_type)->orWhere('username', $request->input_type)->first();
            if ($user) {
                if ($user->first_login) {
                    return 'change password page';
                    return redirect('first-login?id=' . $user->id);
                } else {
                    $request->authenticate();
    
                    $request->session()->regenerate();
                    return redirect()->intended(RouteServiceProvider::HOME);
                }
            }
            else {
                Alert::warning('Peringatan', 'Akun tidak ditemukan');
                return back();
            }
        }
        catch (\Exception $e) {
            Alert::warning('Peringatan', $e->getMessage());
            return back();
        }

        return view('login');
    }

    public function destroy(Request $request)
    {
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
        }elseif(Auth::guard('karyawan')->check()){
            Auth::guard('karyawan')->logout();
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
