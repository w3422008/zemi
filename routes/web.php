<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/ranking', function () {
    return view('ranking');
})->name('ranking');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// 模擬試験機能のルート
Route::middleware('auth')->group(function () {
    Route::get('/exam/prepare/{category}', [App\Http\Controllers\ExamController::class, 'prepare'])->name('exam.prepare');
    Route::get('/exam/start/{category}', [App\Http\Controllers\ExamController::class, 'start'])->name('exam.start');
    Route::post('/exam/finish', [App\Http\Controllers\ExamController::class, 'finish'])->name('exam.finish');
    Route::get('/exam/result/{scoreId}', [App\Http\Controllers\ExamController::class, 'result'])->name('exam.result');
});
