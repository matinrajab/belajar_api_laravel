<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Models\Comment;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    // karena sebelum logout harus login terlebih dahulu, maka jangan lupa beri middleware(['auth:sanctum'])
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    // mengecek siapa pemilik token
    Route::get('/me', [AuthenticationController::class, 'me']);
    // store dipindah pada auth karena untuk melakukan store harus sudah login
    Route::post('/post', [PostController::class, 'store']);
    // mengedit postingan
    // beri middleware agar hanya pemilik postingan yang dapat mengupdate postingannya
    Route::put('/post/{id}', [PostController::class, 'update'])->middleware('pemilik-postingan');
    // menghapus postingan
    // beri middleware agar hanya pemilik postingan yang dapat menghapus postingannya
    Route::delete('/post/{id}', [PostController::class, 'destroy'])->middleware('pemilik-postingan');

    // store comment
    Route::post('/comment', [CommentController::class, 'store']);
    // mengedit komentar
    // beri middleware agar hanya pemilik komentar yang dapat mengupdate komentarnya
    Route::patch('/comment/{id}', [CommentController::class, 'update'])->middleware('pemilik-komentar');
    // menghapus komentar
    // beri middleware agar hanya pemilik komentar yang dapat menghapus komentarnya
    Route::delete('/comment/{id}', [CommentController::class, 'destroy'])->middleware('pemilik-komentar');
});

Route::get('/post', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::get('/post2/{id}', [PostController::class, 'show2']);

Route::post('/login', [AuthenticationController::class, 'login']);
