<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MypageController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ItemController::class, 'index'])->name('index');


Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');
Route::post('mypage/profile', [MypageController::class, 'update'])->name('mypage.update');

