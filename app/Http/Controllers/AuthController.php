<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    static $validation_rules = [
        'email.required' => 'Поле "Email" обязательно для заполнения.',
        'email.email' => 'Некорректный формат Email адреса.',
        'password.required' => 'Поле "Пароль" обязательно к заполнению',
        'password.min' => 'Пароль должен быть больше 8 символов',
    ];

    public function form()
    {
        return view('login-form');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8']
        ], self::$validation_rules);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return redirect('/admin');
        } else {
            return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
