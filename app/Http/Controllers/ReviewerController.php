<?php

namespace App\Http\Controllers;

use App\Models\Rps;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ReviewerController extends Controller
{
    /**
     * Show RPS list for reviewer to review
     */
    public function reviewRps(Request $request, string $code)
    {
        // Ambil semua semester yang ada dari database
        $semesterList = MataKuliah::select('semester')
            ->distinct()
            ->whereNotNull('semester')
            ->orderBy('semester')
            ->pluck('semester')
            ->toArray();

        // Jika tidak ada data semester, gunakan default 1-8
        if (empty($semesterList)) {
            $semesterList = [1, 2, 3, 4, 5, 6, 7, 8];
        }

        // Format data semester dengan mata kuliah dari database
        $semesters = [];
        foreach ($semesterList as $semester) {
            $mataKuliah = MataKuliah::where('semester', $semester)
                ->orderBy('nama_matakuliah')
                ->get();

            $courses = $mataKuliah->map(function ($mk) {
                // Cari RPS yang perlu direview (status draft atau submitted)
                $rps = Rps::where('kode_matakuliah', $mk->kode_matakuliah)
                    ->whereIn('status', ['draft', 'submitted'])
                    ->with('dosen') // Load dosen relationship
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                return [
                    'kode' => $mk->kode_matakuliah ?? $mk->id ?? '',
                    'name' => $mk->nama_matakuliah ?? '',
                    'sks' => $mk->sks ?? 0,
                    'semester' => $mk->semester ?? 0,
                    'rps' => $rps,
                    'has_rps' => $rps !== null,
                    'rps_id' => $rps ? $rps->rps_id : null,
                    'rps_status' => $rps ? $rps->status : null,
                    'dosen_name' => $rps && $rps->dosen ? $rps->dosen->nama : ($rps ? $rps->dosen_pengembang : null),
                    'created_at' => $rps ? $rps->created_at->format('d M Y H:i') : null,
                ];
            })->filter(function ($course) {
                // Hanya tampilkan course yang memiliki RPS untuk direview
                return $course['has_rps'];
            })->values()->toArray();

            if (!empty($courses)) {
                $semesters[] = [
                    'value' => $semester,
                    'label' => 'Semester ' . $semester,
                    'courses' => $courses,
                ];
            }
        }

        return view('reviewer.reviewer_review_rps', [
            'code' => $code,
            'semesters' => $semesters,
        ]);
    }

    /**
     * Show RPS detail for review
     */
    public function showRpsDetail(string $code, int $rps_id)
    {
        $rps = Rps::with(['dosen', 'aktivitasPembelajaran'])
            ->where('rps_id', $rps_id)
            ->whereIn('status', ['draft', 'submitted'])
            ->firstOrFail();

        return view('reviewer.reviewer_rps_detail', [
            'code' => $code,
            'rps' => $rps,
        ]);
    }

    /**
     * View RPS PDF for review
     */
    public function viewRpsPdf(string $code, int $rps_id)
    {
        $rps = Rps::where('rps_id', $rps_id)
            ->whereIn('status', ['draft', 'submitted'])
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
     * Approve RPS
     */
    public function approveRps(Request $request, string $code, int $rps_id)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $rps = Rps::where('rps_id', $rps_id)
                ->whereIn('status', ['draft', 'submitted'])
                ->firstOrFail();

            $rps->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'reviewer_notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()
                ->route('reviewer.review_rps', ['code' => $code])
                ->with('success', 'RPS berhasil disetujui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Gagal menyetujui RPS: ' . $e->getMessage());
        }
    }

    /**
     * Reject RPS
     */
    public function rejectRps(Request $request, string $code, int $rps_id)
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ], [
            'notes.required' => 'Catatan penolakan wajib diisi',
        ]);

        DB::beginTransaction();
        try {
            $rps = Rps::where('rps_id', $rps_id)
                ->whereIn('status', ['draft', 'submitted'])
                ->firstOrFail();

            $rps->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'reviewer_notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()
                ->route('reviewer.review_rps', ['code' => $code])
                ->with('success', 'RPS telah ditolak dengan catatan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Gagal menolak RPS: ' . $e->getMessage());
        }
    }
}
