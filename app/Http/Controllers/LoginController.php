<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login', ['title' => 'Login']);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $remember=$request->remember;

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // todo: add user id in session, set id user in every crud functions
            return redirect()->intended('/labels');
        }

        return back()->with('loginError', 'Login failed');
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }
}
