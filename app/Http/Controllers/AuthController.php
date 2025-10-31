<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','string'],
            'password' => ['required','string'],
        ]);

        // Support username (nama) or email in one field
        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'email';

        if (Auth::attempt([$field => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->withErrors(['email' => 'Kredensial tidak valid'])->onlyInput('email');
    }

    public function home()
    {
        return view('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
