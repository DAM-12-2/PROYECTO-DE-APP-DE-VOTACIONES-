<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

            if ($user->must_change_password) {
                return redirect()->route('password.change');
            }

            return redirect($this->homeFor($user));
        }

        return back()->withErrors(['error' => 'Credenciales incorrectas']);
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->update([
            'password' => $data['password'],
            'must_change_password' => false,
        ]);

        return redirect($this->homeFor($user))->with('success', 'Contraseña actualizada correctamente.');
    }

    private function homeFor($user): string
    {
        return match ($user->role) {
            'jrv' => '/jrv',
            'tee' => '/tribunal',
            default => '/admin',
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
