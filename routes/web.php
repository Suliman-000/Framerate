<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::resource('posts', PostController::class)->only(['create', 'store']);
    Route::resource('posts.comment', CommentController::class)->shallow()->only(['store', 'update', 'destroy']);
});

Route::get('/posts/{post}/{slug}', [PostController::class, 'show'])->name('posts.show');

Route::resource('posts', PostController::class)->only(['index']);
