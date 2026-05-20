<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

Route::get('/posts/trash', [PostController::class, 'trash'])->name('posts.trash');
Route::get('/posts/restore/{id}', [PostController::class, 'restore'])->name('posts.restore');
Route::get('/posts/delete/{id}', [PostController::class, 'forceDelete'])->name('posts.forceDelete');

Route::post('/posts/bulk-action', [PostController::class, 'bulkAction'])->name('posts.bulkAction');