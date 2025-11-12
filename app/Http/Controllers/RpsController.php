<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Rps;
use App\Models\RpsAktivitasPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

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

    /**
     * Simpan data RPS dan generate PDF
     */
    public function store(Request $request, string $code)
    {
        // Log request data for debugging
        \Log::info('RPS Store Request', [
            'all_data' => $request->all(),
            'user_id' => Auth::id(),
        ]);

        // Konversi sks dan semester ke integer sebelum validasi
        $request->merge([
            'sks' => (int) $request->sks,
            'semester' => (int) $request->semester,
        ]);

        // Validasi input dengan pesan error yang lebih jelas
        $validated = $request->validate([
            'kode_matakuliah' => 'required|string',
            'nama_matakuliah' => 'required|string',
            'sks' => 'required|integer|min:1',
            'semester' => 'required|integer|min:1',
            'bahan_kajian' => 'nullable|string',
            'tanggal_penyusunan' => 'required|string',
            'koordinasi_bk' => 'required|string',
            'cpl_prodi' => 'required|array|min:1',
            'indikator' => 'required|array|min:1',
            'cpmk_kode' => 'nullable|array',
            'cpmk_deskripsi' => 'nullable|array',
            'asesmen_jenis' => 'nullable|array',
            'asesmen_bobot' => 'nullable|array',
            'deskripsi_mk' => 'required|string',
            'materi_pembelajaran' => 'nullable|array',
            'pustaka_utama' => 'nullable|array',
            'pustaka_pendukung' => 'nullable|array',
            'perangkat_lunak' => 'nullable|array',
            'perangkat_keras' => 'nullable|array',
            'dosen_pengampu' => 'nullable|array',
            'mk_prasyarat_nama' => 'nullable|array',
            'aktivitas_minggu' => 'nullable|array',
            'aktivitas_cpmk' => 'nullable|array',
        ], [
            'kode_matakuliah.required' => 'Kode mata kuliah wajib diisi',
            'nama_matakuliah.required' => 'Nama mata kuliah wajib diisi',
            'sks.required' => 'SKS wajib diisi',
            'semester.required' => 'Semester wajib diisi',
            'tanggal_penyusunan.required' => 'Tanggal penyusunan wajib diisi',
            'koordinasi_bk.required' => 'Koordinasi BK wajib dipilih',
            'cpl_prodi.required' => 'CPL-PRODI wajib dipilih minimal 1',
            'indikator.required' => 'Indikator wajib dipilih minimal 1',
            'deskripsi_mk.required' => 'Deskripsi mata kuliah wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            // Persiapkan data CPMK
            $cpmkData = [];
            if ($request->has('cpmk_kode') && is_array($request->cpmk_kode)) {
                foreach ($request->cpmk_kode as $index => $kode) {
                    if (!empty($kode)) {
                        $cpmkData[] = [
                            'kode' => $kode,
                            'deskripsi' => $request->cpmk_deskripsi[$index] ?? '',
                        ];
                    }
                }
            }

            // Persiapkan data Asesmen
            $asesmenData = [];
            if ($request->has('asesmen_jenis') && is_array($request->asesmen_jenis)) {
                foreach ($request->asesmen_jenis as $index => $jenis) {
                    if (!empty($jenis)) {
                        $asesmenData[] = [
                            'jenis' => $jenis,
                            'bobot' => $request->asesmen_bobot[$index] ?? 0,
                            'keterangan' => $request->asesmen_keterangan[$index] ?? '',
                        ];
                    }
                }
            }

            // Persiapkan data MK Prasyarat
            $mkPrasyaratData = [];
            if ($request->has('mk_prasyarat_nama')) {
                foreach ($request->mk_prasyarat_nama as $nama) {
                    if (!empty($nama)) {
                        $mkPrasyaratData[] = ['nama' => $nama];
                    }
                }
            }

            // Persiapkan data korelasi
            $korelasiData = $request->korelasi ?? [];

            // Simpan RPS
            $rps = Rps::create([
                'dosen_id' => Auth::id(),
                'template_id' => null,
                'kode_matakuliah' => $request->kode_matakuliah,
                'nama_matakuliah' => $request->nama_matakuliah,
                'sks' => $request->sks,
                'semester' => $request->semester,
                'bahan_kajian' => $request->bahan_kajian,
                'tanggal_penyusunan' => $request->tanggal_penyusunan,
                'dosen_pengembang' => $request->dosen_pengembang,
                'dosen_pengembang_id' => $request->dosen_pengembang_id,
                'koordinasi_bk' => $request->koordinasi_bk,
                'kaprodi' => $request->kaprodi ?? 'Ricky Akbar, S.Kom, M.Kom',
                'cpl_prodi' => $request->cpl_prodi,
                'indikator' => $request->indikator,
                'cpmk' => $cpmkData,
                'korelasi' => $korelasiData,
                'asesmen' => $asesmenData,
                'deskripsi_mk' => $request->deskripsi_mk,
                'materi_pembelajaran' => $request->materi_pembelajaran ?? [],
                'pustaka_utama' => $request->pustaka_utama,
                'pustaka_pendukung' => $request->pustaka_pendukung ?? [],
                'perangkat_lunak' => $request->perangkat_lunak ?? [],
                'perangkat_keras' => $request->perangkat_keras ?? [],
                'dosen_pengampu' => $request->dosen_pengampu ?? [],
                'mk_prasyarat' => $mkPrasyaratData,
                'status' => 'submitted',
                'submitted_at' => now(),
                'template_content' => '',
            ]);

            // Simpan Aktivitas Pembelajaran
            if ($request->has('aktivitas_minggu')) {
                foreach ($request->aktivitas_minggu as $index => $minggu) {
                    RpsAktivitasPembelajaran::create([
                        'rps_id' => $rps->rps_id,
                        'minggu_ke' => $minggu,
                        'cpmk_kode' => $request->aktivitas_cpmk[$index] ?? '',
                        'indikator_penilaian' => $request->aktivitas_indikator_penilaian[$index] ?? '',
                        'bentuk_penilaian_jenis' => $request->aktivitas_bentuk_penilaian_jenis[$index] ?? '',
                        'bentuk_penilaian_bobot' => $request->aktivitas_bentuk_penilaian_bobot[$index] ?? 0,
                        'aktivitas_sinkron_luring' => $request->aktivitas_sinkron_luring[$index] ?? '',
                        'aktivitas_sinkron_daring' => $request->aktivitas_sinkron_daring[$index] ?? '',
                        'aktivitas_asinkron_mandiri' => $request->aktivitas_asinkron_mandiri[$index] ?? '',
                        'aktivitas_asinkron_kolaboratif' => $request->aktivitas_asinkron_kolaboratif[$index] ?? '',
                        'media' => $request->aktivitas_media[$index] ?? '',
                        'materi_pembelajaran' => $request->aktivitas_materi_pembelajaran[$index] ?? '',
                        'referensi' => $request->aktivitas_referensi[$index] ?? '',
                        'urutan' => $index + 1,
                    ]);
                }
            }

            DB::commit();

            // Generate dan simpan PDF ke storage
            $pdf = Pdf::loadView('pdf.rps_template', ['rps' => $rps]);
            $filename = 'RPS_' . $rps->kode_matakuliah . '_' . date('Ymd_His') . '.pdf';
            $filepath = 'rps/' . $filename;
            
            // Simpan PDF ke storage/app/public/rps
            Storage::disk('public')->put($filepath, $pdf->output());
            
            // Update path PDF di database
            $rps->update(['pdf_path' => $filepath]);

            // Redirect ke halaman RPS list dengan success message
            return redirect()
                ->to(route('fakultas.rps', ['code' => $code]) . '?role=dosen')
                ->with('success', 'RPS berhasil disimpan dan PDF telah di-generate!')
                ->with('new_rps_id', $rps->rps_id);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('RPS Store Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()
                ->with('error', 'Gagal menyimpan RPS: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * View RPS PDF in browser
     */
    public function viewPdf(string $code, int $rps_id)
    {
        $rps = Rps::findOrFail($rps_id);
        
        // Cek apakah PDF sudah ada di storage
        if ($rps->pdf_path && Storage::disk('public')->exists($rps->pdf_path)) {
            $filepath = storage_path('app/public/' . $rps->pdf_path);
            return response()->file($filepath);
        }
        
        // Jika belum ada, generate ulang
        $rps->load('aktivitasPembelajaran');
        $pdf = Pdf::loadView('pdf.rps_template', ['rps' => $rps]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('RPS_' . $rps->kode_matakuliah . '.pdf');
    }

    /**
     * Download RPS sebagai PDF
     */
    public function downloadPdf(string $code, int $rps_id)
    {
        $rps = Rps::findOrFail($rps_id);
        
        // Cek apakah PDF sudah ada di storage
        if ($rps->pdf_path && Storage::disk('public')->exists($rps->pdf_path)) {
            $filepath = storage_path('app/public/' . $rps->pdf_path);
            return response()->download($filepath, 'RPS_' . $rps->kode_matakuliah . '.pdf');
        }
        
        // Jika belum ada, generate ulang
        $rps->load('aktivitasPembelajaran');
        $pdf = Pdf::loadView('pdf.rps_template', ['rps' => $rps]);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'RPS_' . $rps->kode_matakuliah . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * View RPS PDF in browser for students (only approved RPS)
     */
    public function viewPdfStudent(string $code, int $rps_id)
    {
        $rps = Rps::where('rps_id', $rps_id)
                   ->where('status', 'approved')
                   ->firstOrFail();
        
        // Cek apakah PDF sudah ada di storage
        if ($rps->pdf_path && Storage::disk('public')->exists($rps->pdf_path)) {
            $filepath = storage_path('app/public/' . $rps->pdf_path);
            return response()->file($filepath);
        }
        
        // Jika belum ada, generate ulang
        $rps->load('aktivitasPembelajaran');
        $pdf = Pdf::loadView('pdf.rps_template', ['rps' => $rps]);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('RPS_' . $rps->kode_matakuliah . '.pdf');
    }

    /**
     * Download RPS sebagai PDF for students (only approved RPS)
     */
    public function downloadPdfStudent(string $code, int $rps_id)
    {
        $rps = Rps::where('rps_id', $rps_id)
                   ->where('status', 'approved')
                   ->firstOrFail();
        
        // Cek apakah PDF sudah ada di storage
        if ($rps->pdf_path && Storage::disk('public')->exists($rps->pdf_path)) {
            $filepath = storage_path('app/public/' . $rps->pdf_path);
            return response()->download($filepath, 'RPS_' . $rps->kode_matakuliah . '.pdf');
        }
        
        // Jika belum ada, generate ulang
        $rps->load('aktivitasPembelajaran');
        $pdf = Pdf::loadView('pdf.rps_template', ['rps' => $rps]);
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'RPS_' . $rps->kode_matakuliah . '_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }
}
