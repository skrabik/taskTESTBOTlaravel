<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use \App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

Route::post('/webhook', 'WebHookController@handler');

Route::middleware([RedirectIfAuthenticated::class])->group(function () {
    Route::get('/', 'AuthController@form');
    Route::post('/auth/login', 'AuthController@login');

    Route::get('/register', 'RegisterController@form');
    Route::post('/auth/register', 'RegisterController@register');
});

Route::get('/logout', 'AuthController@logout');

Route::post('/mailing', 'MailingController@sendAll');
