<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    static $validation_rules = [
        'name.required' => 'Поле "Имя" обязательно для заполнения',
        'name.max' => 'Имя не должно превышать 20 символов',
        'email.required' => 'Поле "Email" обязательно для заполнения',
        'email.email' => 'Некорректный формат Email адреса',
        'email.unique' => 'Пользователь с таким Email уже существует',
        'password.required' => 'Поле "Пароль" обязательно к заполнению',
        'password.min' => 'Пароль должен быть больше 8 символов',
    ];

    public function form ()
    {
        return view('register-form');
    }

    public function register (Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8']
        ], self::$validation_rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        Auth::login($user, $request->boolean('remember'));

        return redirect('/admin');
    }
}
