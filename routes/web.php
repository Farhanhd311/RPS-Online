<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\RpsController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\MataKuliahController;

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
    Route::get('/fakultas/{code}/struktur', function (string $code) {
        return view('mahasiswa.mahasiswa_struktur', ['code' => $code]);
    })->name('fakultas.struktur');
    
    // Dosen routes
    Route::get('/dosen/{code}/input-rps', [RpsController::class, 'showInputForm'])->name('dosen.input_rps');
    Route::post('/dosen/{code}/input-rps', [RpsController::class, 'store'])->name('rps.store');
    Route::get('/dosen/{code}/rps/{rps_id}/view', [RpsController::class, 'viewPdf'])->name('rps.view');
    Route::get('/dosen/{code}/rps/{rps_id}/download', [RpsController::class, 'downloadPdf'])->name('rps.download');
    
    // Mata Kuliah routes for Dosen
    Route::post('/dosen/{code}/mata-kuliah', [MataKuliahController::class, 'store'])->name('dosen.mata_kuliah.store');
    Route::get('/dosen/{code}/mata-kuliah/semester/{semester}', [MataKuliahController::class, 'getBySemester'])->name('dosen.mata_kuliah.by_semester');
    Route::delete('/dosen/{code}/mata-kuliah/{id}', [MataKuliahController::class, 'destroy'])->name('dosen.mata_kuliah.destroy');
    
    // Reviewer routes
    Route::get('/reviewer/{code}/review-rps', [ReviewerController::class, 'reviewRps'])->name('reviewer.review_rps');
    Route::get('/reviewer/{code}/rps/{rps_id}/detail', [ReviewerController::class, 'showRpsDetail'])->name('reviewer.rps.detail');
    Route::get('/reviewer/{code}/rps/{rps_id}/view', [ReviewerController::class, 'viewRpsPdf'])->name('reviewer.rps.view');
    Route::post('/reviewer/{code}/rps/{rps_id}/approve', [ReviewerController::class, 'approveRps'])->name('reviewer.rps.approve');
    Route::post('/reviewer/{code}/rps/{rps_id}/reject', [ReviewerController::class, 'rejectRps'])->name('reviewer.rps.reject');
    
    // Student routes for RPS PDF
    Route::get('/mahasiswa/{code}/rps/{rps_id}/view', [RpsController::class, 'viewPdfStudent'])->name('rps.view.student');
    Route::get('/mahasiswa/{code}/rps/{rps_id}/download', [RpsController::class, 'downloadPdfStudent'])->name('rps.download.student');
});
