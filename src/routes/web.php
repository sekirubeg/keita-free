<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Models\Item;



// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');
Route::post('mypage/profile', [MypageController::class, 'update'])->name('mypage.update');


Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
Route::get('/item/{id}', [ItemController::class, 'show'])->name('item.show');

Route::post('/likes/{id}', [LikeController::class, 'store'])->name('like');
Route::delete('/likes/{id}', [LikeController::class, 'destroy'])->name('unlike');

Route::get('purchase/success', [PurchaseController::class, 'success'])->name('item.purchase.success');
Route::get('purchase/{id}', [PurchaseController::class, 'index'])->name('item.purchase');
Route::get('purchase/address/{id}', [PurchaseController::class, 'address'])->name('item.address');
Route::post('purchase/address/{id}', [PurchaseController::class, 'change'])->name('address.store');
Route::get('purchase/checkout/{id}', [PurchaseController::class, 'checkout'])->name('item.checkout');

Route::post('/comments', [CommentController::class, 'store'])->name('comment.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');

//テスト時のlogoutルートを確保
if (app()->environment('testing')) {
    Route::post('/logout', function () {
        return redirect('/'); // または何もしない
    })->name('logout');
}