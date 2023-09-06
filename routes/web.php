<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/token-session', function (Request $request){

    if(!session()->has('current_token')) {
        session()->put('current_token', $request
            ->user()
            ->createToken(strtotime('now'), ['*'], new \Datetime('+1 day'))
            ->plainTextToken
        );
    }

    return ['token' => session('current_token')];
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
