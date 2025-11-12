<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MataKuliahController extends Controller
{
    /**
     * Store a new mata kuliah
     */
    public function store(Request $request, string $code)
    {
        // Validasi input
        $validated = $request->validate([
            'kode_matakuliah' => 'required|string|max:20|unique:mata_kuliah,kode_matakuliah',
            'nama_matakuliah' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
        ], [
            'kode_matakuliah.required' => 'Kode mata kuliah wajib diisi',
            'kode_matakuliah.unique' => 'Kode mata kuliah sudah ada',
            'nama_matakuliah.required' => 'Nama mata kuliah wajib diisi',
            'sks.required' => 'SKS wajib diisi',
            'sks.min' => 'SKS minimal 1',
            'sks.max' => 'SKS maksimal 6',
            'semester.required' => 'Semester wajib diisi',
            'semester.min' => 'Semester minimal 1',
            'semester.max' => 'Semester maksimal 8',
        ]);

        DB::beginTransaction();
        try {
            // Simpan mata kuliah baru
            $mataKuliah = MataKuliah::create([
                'kode_matakuliah' => strtoupper($validated['kode_matakuliah']),
                'nama_matakuliah' => $validated['nama_matakuliah'],
                'sks' => $validated['sks'],
                'semester' => $validated['semester'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil ditambahkan!',
                'data' => $mataKuliah
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error creating mata kuliah', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan mata kuliah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mata kuliah by semester for AJAX
     */
    public function getBySemester(Request $request, string $code, int $semester)
    {
        try {
            $mataKuliah = MataKuliah::where('semester', $semester)
                ->orderBy('nama_matakuliah')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $mataKuliah
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data mata kuliah'
            ], 500);
        }
    }

    /**
     * Delete mata kuliah
     */
    public function destroy(Request $request, string $code, int $id)
    {
        DB::beginTransaction();
        try {
            $mataKuliah = MataKuliah::findOrFail($id);
            
            // Cek apakah mata kuliah sudah digunakan di RPS
            $rpsCount = \App\Models\Rps::where('kode_matakuliah', $mataKuliah->kode_matakuliah)->count();
            
            if ($rpsCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mata kuliah tidak dapat dihapus karena sudah digunakan dalam RPS'
                ], 400);
            }

            $mataKuliah->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus mata kuliah: ' . $e->getMessage()
            ], 500);
        }
    }
}
