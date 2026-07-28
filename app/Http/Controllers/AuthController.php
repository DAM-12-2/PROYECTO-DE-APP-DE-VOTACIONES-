<?php namespace App\Http\Controllers;

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
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt(['name' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 'jrv') {
                return redirect('/jrv');
            }
            if ($user->role === 'tee') {
                return redirect('/tribunal');
            }
            return redirect('/admin');
        }

        return back()->withErrors(['error' => 'Credenciales incorrectas']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
