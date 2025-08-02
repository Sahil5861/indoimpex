<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginUserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();
        if ($user->status == 0) {
            return back()->withErrors(['email' => 'You are blocked by the admin.']);
        }

        if (Auth::attempt($credentials)) {
            // Authentication passed, redirect to intended URL or dashboard
            return redirect()->intended(route('dashboard'));
        }

        // Authentication failed, redirect back with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout user

        $request->session()->invalidate(); // Invalidate the session

        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('login'); // Redirect to login page after logout
    }
}
