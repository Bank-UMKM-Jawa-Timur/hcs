<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    
    public function create(): View
    {
        return view('auth.login');
    }

    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // } 

    public function store(Request $request)
    {
        $request->validate([
            'email'=>'required|string',
            'password'=>'required'
         ]);

        if (Auth::guard('karyawan')->attempt(['nip' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }elseif(Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        Session::flash('status', 'failed');
        Session::flash('message', 'Email/Nip atau password salah.');
        return view('auth.login');
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
