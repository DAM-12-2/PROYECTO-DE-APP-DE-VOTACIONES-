<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $usuario = User::where('name', $request->username)->first();

        if ($usuario && $usuario->verificarPassword($request->password)) {
            Auth::login($usuario);
            $request->session()->regenerate();

            if ($usuario->must_change_password) {
                return redirect()->route('password.change');
            }

            return redirect($this->homeFor($usuario));
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

        if (!$user->verificarPassword($data['current_password'])) {
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
