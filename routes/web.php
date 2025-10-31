<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacultyController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Selalu tampilkan halaman login saat mengakses root
Route::get('/', [AuthController::class, 'showLogin']);

Route::middleware('auth')->group(function () {
    Route::get('/home', [AuthController::class, 'home'])->name('home');
    Route::get('/fakultas', [FacultyController::class, 'index'])->name('fakultas.index');
    Route::get('/fakultas/{code}/program-studi', [FacultyController::class, 'programs'])->name('fakultas.programs');
    Route::get('/fakultas/{code}/program-studi/{slug}', [FacultyController::class, 'programDetail'])->name('fakultas.program.detail');
    Route::get('/fakultas/{code}/rps', [FacultyController::class, 'rps'])->name('fakultas.rps');
});
