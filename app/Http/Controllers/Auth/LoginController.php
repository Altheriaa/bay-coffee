<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function index() {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = Str::lower($request->input('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors(['email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik."]);
        }

        $credentials = $request->only('email', 'password');

        if (! $credentials) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ]);
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect('/admin')->with('success', 'Login berhasil.');
            }
            if (Auth::user()->isPelanggan()) {
                return redirect('/')->with('success', 'Login berhasil.');
            }
        }

        RateLimiter::hit($throttleKey, 60);
        return back()->withErrors(['email' => 'Email atau Password salah']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
