<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }


public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('main')->with('message', 'Login successful!');
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
}


    public function logout()
    {
        Auth::logout();
        Session::flush();
    
        return redirect()->route('login');
    }
    
}
