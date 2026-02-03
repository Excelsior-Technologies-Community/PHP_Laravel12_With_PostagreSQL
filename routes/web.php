<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function () {
    return view('welcome');
});

// ------------------
// POSTS CRUD MANUAL
// ------------------

//  List all posts
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

//  Show create post form
Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');

//  Store new post
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

//  Show edit post form
Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');

//  Update post
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

//  Delete post
Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

