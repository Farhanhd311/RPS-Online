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
        
        // Data CPL-PRODI (kode dan deskripsi terpisah)
        $cplCodes = ['CP-1', 'CP-2', 'CP-3', 'CP-4', 'CP-5', 'CP-6', 'CP-7', 'CP-8'];
        $cplDescriptions = [
            'CP-1' => 'Kemampuan untuk mengidentifikasi, memformulasikan dan memecahkan permasalahan kebutuhan informasi dari suatu organisasi',
            'CP-2' => 'Kemampuan untuk mengintegrasikan solusi berbasis teknologi informasi secara efektif pada suatu organisasi',
            'CP-3' => 'Kemampuan untuk menerapkan konsep-konsep dasar dalam merencanakan Sistem Informasi, merancang Sistem Informasi, membangun Sistem Informasi, mengoperasikan Sistem Informasi, dan mengevaluasi Sistem Informasi',
            'CP-4' => 'Kemampuan untuk berkarya dengan perilaku etika sesuai bidang keprofesian teknologi informasi',
            'CP-5' => 'Kemampuan untuk berkomunikasi secara efektif pada berbagai kalangan',
            'CP-6' => 'Kemampuan untuk melibatkan diri dalam proses belajar terus-menerus sepanjang hidup',
            'CP-7' => 'Kemampuan untuk bekerja-sama secara efektif baik sebagai anggota maupun pimpinan tim kerja',
            'CP-8' => 'Kemampuan untuk mengidentifikasi kebutuhan untuk menjadi seorang wirausaha di bidang teknologi informasi',
        ];
        
        // Data Indikator (IK) (kode dan deskripsi terpisah)
        $ikCodes = ['IK02-01', 'IK02-02', 'IK03-01'];
        $ikDescriptions = [
            'IK02-01' => 'Mampu mengidentifikasi solusi terintegrasi yang tepat dan handal',
            'IK02-02' => 'Mampu merancang integrasi sistem yang meningkatkan daya saing organisasi',
            'IK03-01' => 'Mampu menerapkan konsep-konsep dasar dalam merencanakan Sistem Informasi',
        ];
        
        // Data Komponen Asesmen
        $asesmenModels = [
            'Tugas-Pr',
            'Kuis',
            'UTS',
            'Tugas Besar',
            'UAS',
        ];
        
        return view('dosen.dosen_input_rps', [
            'code' => $code,
            'allMataKuliah' => $allMataKuliah,
            'semesters' => $semesters,
            'dosenPengembang' => $dosenPengembang,
            'dosenPengembangId' => $dosenPengembangId,
            'cplCodes' => $cplCodes,
            'cplDescriptions' => $cplDescriptions,
            'ikCodes' => $ikCodes,
            'ikDescriptions' => $ikDescriptions,
            'asesmenModels' => $asesmenModels,
        ]);
    }
}
