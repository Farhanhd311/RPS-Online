<?php

namespace App\Http\Controllers;

use App\Models\RpsSuggestion;
use App\Models\Rps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RpsSuggestionController extends Controller
{
    /**
     * Get all suggestions for a specific RPS
     */
    public function getSuggestions($rpsId)
    {
        try {
            $suggestions = RpsSuggestion::where('rps_id', $rpsId)
                ->with('mahasiswa')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($suggestion) {
                    return [
                        'suggestion_id' => $suggestion->suggestion_id,
                        'rps_id' => $suggestion->rps_id,
                        'username' => $suggestion->mahasiswa->nama ?? 'Unknown',
                        'saran' => $suggestion->saran,
                        'status' => $suggestion->status,
                        'created_at' => $suggestion->created_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new suggestion
     */
    public function storeSuggestion(Request $request, $rpsId)
    {
        try {
            $request->validate([
                'saran' => 'required|string|min:5|max:1000',
            ], [
                'saran.required' => 'Masukan tidak boleh kosong',
                'saran.min' => 'Masukan minimal 5 karakter',
                'saran.max' => 'Masukan maksimal 1000 karakter',
            ]);

            // Verify RPS exists
            $rps = Rps::findOrFail($rpsId);

            // Create suggestion
            $suggestion = RpsSuggestion::create([
                'rps_id' => $rpsId,
                'mahasiswa_id' => Auth::id(),
                'saran' => $request->saran,
                'status' => 'pending',
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Masukan berhasil dikirim',
                'suggestion' => [
                    'suggestion_id' => $suggestion->suggestion_id,
                    'rps_id' => $suggestion->rps_id,
                    'username' => Auth::user()->nama,
                    'saran' => $suggestion->saran,
                    'status' => $suggestion->status,
                    'created_at' => $suggestion->created_at,
                ]
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get suggestions for reviewer/dosen dashboard
     */
    public function getDashboardSuggestions()
    {
        try {
            $user = Auth::user();
            
            // Get RPS created by this user (for dosen)
            $rpsIds = Rps::where('dosen_id', $user->user_id)
                ->pluck('rps_id')
                ->toArray();

            // Get all suggestions for these RPS
            $suggestions = RpsSuggestion::whereIn('rps_id', $rpsIds)
                ->with('mahasiswa', 'rps')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($suggestion) {
                    return [
                        'suggestion_id' => $suggestion->suggestion_id,
                        'rps_id' => $suggestion->rps_id,
                        'rps_name' => $suggestion->rps->nama_matakuliah ?? 'Unknown',
                        'mahasiswa_username' => $suggestion->mahasiswa->nama ?? 'Unknown',
                        'saran' => $suggestion->saran,
                        'status' => $suggestion->status,
                        'created_at' => $suggestion->created_at,
                    ];
                });

            return response()->json([
                'success' => true,
                'suggestions' => $suggestions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update suggestion status (for reviewer/dosen)
     */
    public function updateStatus(Request $request, $suggestionId)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,reviewed,approved',
            ]);

            $suggestion = RpsSuggestion::findOrFail($suggestionId);
            
            // Verify user is the RPS owner
            $rps = $suggestion->rps;
            if ($rps->dosen_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $suggestion->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui',
                'suggestion' => $suggestion
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
