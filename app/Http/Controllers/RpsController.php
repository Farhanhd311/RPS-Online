<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RpsController extends Controller
{
    /**
     * Menampilkan halaman form input RPS untuk dosen
     */
    public function showInputForm(Request $request, string $code)
    {
        // Ambil semua mata kuliah untuk dropdown
        $allMataKuliah = MataKuliah::orderBy('semester')->orderBy('nama_matakuliah')->get();
        
        // Ambil semua semester yang ada
        $semesters = MataKuliah::select('semester')
            ->distinct()
            ->whereNotNull('semester')
            ->orderBy('semester')
            ->pluck('semester')
            ->toArray();
        
        // Jika tidak ada data semester, gunakan default 1-8
        if (empty($semesters)) {
            $semesters = [1, 2, 3, 4, 5, 6, 7, 8];
        }
        
        // Ambil data user yang sedang login (dosen pengembang)
        $user = Auth::user();
        $dosenPengembang = $user->name ?? 'Dosen';
        $dosenPengembangId = $user->id ?? null;
        
        return view('dosen.dosen_input_rps', [
            'code' => $code,
            'allMataKuliah' => $allMataKuliah,
            'semesters' => $semesters,
            'dosenPengembang' => $dosenPengembang,
            'dosenPengembangId' => $dosenPengembangId,
        ]);
    }
}
