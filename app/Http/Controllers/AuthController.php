<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function handleLogin(UserLoginRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt($data)) {
            return back()->with('error', 'Kredensial tidak ditemukan!');
        }

        return redirect('/');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function handleRegister(UserRegistrationRequest $request)
    {
        $validated = $request->validated();

        User::create($validated);

        return redirect()->route('login')->with('message', 'Berhasil daftar! silahkan login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        return redirect()->route('home');
    }
}
