@extends('layouts.app')

@section('content')
<div class="space-y-6 mt-4">
    @php($userRole = auth()->user()->role ?? 'reviewer')
    <div class="flex items-center gap-3">
        <a href="{{ route('reviewer.review_rps', ['code' => $code]) }}" class="inline-flex items-center">
            <img src="{{ asset('images/back.png') }}" alt="Back" class="h-7 w-7 object-contain" />
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-700">Review RPS - {{ $rps->nama_matakuliah }}</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- RPS Information -->
    <div class="bg-white rounded-xl p-6 shadow-sm ring-1 ring-slate-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Informasi RPS</h2>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">{{ $rps->kode_matakuliah }}</span>
                    <span class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full">{{ $rps->sks }} SKS</span>
                    <span class="text-sm text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Semester {{ $rps->semester }}</span>
                    <span class="text-sm font-medium px-3 py-1 rounded-full {{ $rps->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $rps->status === 'draft' ? 'Draft' : 'Submitted' }}
                    </span>
                </div>
            </div>
            <a href="{{ route('reviewer.rps.view', ['code' => $code, 'rps_id' => $rps->rps_id]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                <span class="i-heroicons-eye text-lg"></span>
                <span>Lihat PDF</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">Informasi Dasar</h3>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium">Mata Kuliah:</span> {{ $rps->nama_matakuliah }}</div>
                    <div><span class="font-medium">Kode:</span> {{ $rps->kode_matakuliah }}</div>
                    <div><span class="font-medium">SKS:</span> {{ $rps->sks }}</div>
                    <div><span class="font-medium">Semester:</span> {{ $rps->semester }}</div>
                    <div><span class="font-medium">Tanggal Penyusunan:</span> {{ $rps->tanggal_penyusunan }}</div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold text-slate-800 mb-2">Dosen</h3>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium">Dosen Pengembang:</span> {{ $rps->dosen_pengembang }}</div>
                    <div><span class="font-medium">Koordinasi BK:</span> {{ $rps->koordinasi_bk }}</div>
                    <div><span class="font-medium">Kaprodi:</span> {{ $rps->kaprodi }}</div>
                    <div><span class="font-medium">Dibuat:</span> {{ $rps->created_at->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>

        @if($rps->deskripsi_mk)
        <div class="mt-6">
            <h3 class="font-semibold text-slate-800 mb-2">Deskripsi Mata Kuliah</h3>
            <p class="text-sm text-slate-600 bg-slate-50 p-3 rounded-lg">{{ $rps->deskripsi_mk }}</p>
        </div>
        @endif

        @if($rps->cpl_prodi && count($rps->cpl_prodi) > 0)
        <div class="mt-6">
            <h3 class="font-semibold text-slate-800 mb-2">CPL-PRODI</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($rps->cpl_prodi as $cpl)
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $cpl }}</span>
                @endforeach
            </div>
        </div>
        @endif

        @if($rps->cpmk && count($rps->cpmk) > 0)
        <div class="mt-6">
            <h3 class="font-semibold text-slate-800 mb-2">CPMK</h3>
            <div class="space-y-2">
                @foreach($rps->cpmk as $cpmk)
                    <div class="text-sm bg-slate-50 p-3 rounded-lg">
                        <span class="font-medium text-emerald-600">{{ $cpmk['kode'] ?? '' }}:</span>
                        <span class="text-slate-600">{{ $cpmk['deskripsi'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Review Actions -->
    <div class="bg-white rounded-xl p-6 shadow-sm ring-1 ring-slate-100">
        <h2 class="text-xl font-bold text-slate-900 mb-4">Aksi Review</h2>
        
        <div x-data="{ showApprove: false, showReject: false }" class="space-y-4">
            <!-- Approve Button -->
            <div>
                <button @click="showApprove = !showApprove" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <span class="i-heroicons-check-circle text-lg"></span>
                    <span>Setujui RPS</span>
                </button>
                
                <div x-show="showApprove" x-transition class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <form action="{{ route('reviewer.rps.approve', ['code' => $code, 'rps_id' => $rps->rps_id]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="approve_notes" class="block text-sm font-medium text-slate-700 mb-2">Catatan Persetujuan (Opsional)</label>
                            <textarea name="notes" id="approve_notes" rows="3" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Tambahkan catatan persetujuan..."></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Konfirmasi Setujui
                            </button>
                            <button type="button" @click="showApprove = false" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reject Button -->
            <div>
                <button @click="showReject = !showReject" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <span class="i-heroicons-x-circle text-lg"></span>
                    <span>Tolak RPS</span>
                </button>
                
                <div x-show="showReject" x-transition class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <form action="{{ route('reviewer.rps.reject', ['code' => $code, 'rps_id' => $rps->rps_id]) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="reject_notes" class="block text-sm font-medium text-slate-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="notes" id="reject_notes" rows="4" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Jelaskan alasan penolakan dan saran perbaikan..."></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Konfirmasi Tolak
                            </button>
                            <button type="button" @click="showReject = false" class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Masukan dan Saran dari Mahasiswa -->
    <div x-data="suggestionManager({{ $rps->rps_id }})" @init="init()" class="bg-white rounded-xl p-6 shadow-sm ring-1 ring-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-slate-900">Masukan dan Saran dari Mahasiswa</h2>
            <button @click="loadSuggestions()" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                <span class="i-heroicons-arrow-path text-lg"></span>
            </button>
        </div>

        <div class="space-y-4">
            <template x-for="suggestion in suggestions" :key="suggestion.suggestion_id">
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="font-semibold text-slate-800" x-text="suggestion.username"></p>
                            <p class="text-xs text-slate-500" x-text="formatDate(suggestion.created_at)"></p>
                        </div>
                        <div class="flex items-center gap-2">
                            <select @change="updateStatus(suggestion.suggestion_id, $event.target.value)" :value="suggestion.status" class="text-xs px-2 py-1 rounded-full border border-slate-300 focus:ring-2 focus:ring-emerald-500">
                                <option value="pending">Pending</option>
                                <option value="reviewed">Reviewed</option>
                                <option value="approved">Approved</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-slate-700 text-sm" x-text="suggestion.saran"></p>
                </div>
            </template>

            <div x-show="!suggestions.length" class="text-center py-8 text-slate-500">
                <p class="text-sm">Belum ada masukan dari mahasiswa.</p>
            </div>
        </div>
    </div>
</div>

<script>
function suggestionManager(rpsId) {
    return {
        rpsId: rpsId,
        suggestions: [],
        
        async loadSuggestions() {
            try {
                const response = await fetch(`/api/rps/${this.rpsId}/suggestions`);
                const data = await response.json();
                this.suggestions = data.suggestions || [];
            } catch (error) {
                console.error('Error loading suggestions:', error);
            }
        },

        async updateStatus(suggestionId, status) {
            try {
                const response = await fetch(`/api/suggestions/${suggestionId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ status: status })
                });

                if (response.ok) {
                    await this.loadSuggestions();
                } else {
                    alert('Gagal mengubah status');
                }
            } catch (error) {
                console.error('Error updating status:', error);
            }
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        init() {
            this.loadSuggestions();
        }
    };
}
</script>
@endsection
