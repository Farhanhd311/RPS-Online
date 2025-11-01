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
            
            // Redirect berdasarkan role
            return $this->redirectByRole();
        }

        return back()->withErrors(['email' => 'Kredensial tidak valid'])->onlyInput('email');
    }

    public function home()
    {
        // Redirect berdasarkan role
        return $this->redirectByRole();
    }

    private function redirectByRole()
    {
        $user = Auth::user();
        $role = $user->role ?? 'mahasiswa';

        return match($role) {
            'mahasiswa' => view('mahasiswa.mahasiswa_home'),
            'dosen' => view('dosen.dosen_home'),
            'reviewer' => view('reviewer.reviewer_home'),
            'admin' => view('mahasiswa.mahasiswa_home'), // default untuk admin
            default => view('mahasiswa.mahasiswa_home'), // fallback default
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
