<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LogActivity;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function reset(): View
    {
        return view('auth.reset-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }

    public function updatePassword(Request $request)
    {
        $idUser = auth()->user()->id;
        $user = User::findOrFail($idUser);;
        $old = $request->old_pass;
        $new = $request->password;

        if (!Hash::check($old, $user->password))
            return back()->withError('Password lama tidak cocok.');

        if (Hash::check($new, $user->password))
            return back()->withError('Password baru tidak boleh sama dengan password lama.');

        $validatedData = $request->validate(
            [
                'old_pass' => 'required',
                'password' => 'required',
                'confirmation' => 'required|same:password'
            ],
            [
                'required' => ':attribute harus diisi.',
                'password.unique' => 'Password baru tidak boleh sama dengan password lama.',
                'same' => 'Konfirmasi password harus sesuai.'
            ],
            [
                'old_pass' => 'Password lama',
                'password' => 'Password baru',
                'confirmation' => 'Konfirmasi password baru',
            ]
        );

        try {
            $user->password = Hash::make($request->get('password'));
            $user->updated_at = now();
            $user->save();

            $activity = "Pengguna <b>$user->name</b> melakukan pergantian password";
            LogActivity::create($activity);

            Alert::success('Berhasil', 'Berhasil memperbarui password');
            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (\Illuminate\Database\QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }
    public function resetPassword(Request $request)
    {
        $idUser = auth()->user()->id;
        $user = User::findOrFail($idUser);
        $old = $request->old_pass;
        $new = $request->password;

        if (Hash::check($new, $user->password))
            return redirect()->route('password.reset')->withError('Password baru tidak boleh sama dengan password lama.');

        $validatedData = $request->validate(
            [
                'password' => 'required',
                'confirmation' => 'required|same:password'
            ],
            [
                'required' => ':attribute harus diisi.',
                'password.unique' => 'Password baru tidak boleh sama dengan password lama.',
                'same' => 'Konfirmasi password harus sesuai.'
            ],
            [
                'password' => 'Password baru',
                'confirmation' => 'Konfirmasi password baru',
            ]
        );

        try {
            $user->password = Hash::make($request->get('password'));
            $user->first_login = 0;
            $user->updated_at = now();
            $user->save();

        } catch (\Exception $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            Alert::error('Error', $e->getMessage());
            return redirect()->back();
        }

        Alert::success('Berhasil', 'Berhasil memperbarui password');
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
