<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

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
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::resource('posts', PostController::class)->only(['create', 'store']);
    Route::resource('posts.comment', CommentController::class)->shallow()->only(['store', 'update', 'destroy']);

    Route::post('/likes/{type}/{id}', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{type}/{id}', [LikeController::class, 'destroy'])->name('likes.destroy');
});

Route::get('posts/{topic?}', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');


