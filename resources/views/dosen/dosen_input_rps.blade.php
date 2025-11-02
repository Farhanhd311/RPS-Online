@extends('layouts.app')

@section('content')
<div x-data='inputRpsPage({{ json_encode($allMataKuliah) }}, {{ json_encode($semesters) }}, "{{ $dosenPengembang }}", {{ $dosenPengembangId }})' class="space-y-6 mt-4">
    @php($userRole = auth()->user()->role ?? 'dosen')
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('fakultas.rps', ['code'=>$code]) }}?role={{ $userRole }}" class="inline-flex items-center">
            <img src="{{ asset('images/back.png') }}" alt="Back" class="h-7 w-7 object-contain" />
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-700">Input RPS</h1>
    </div>

    <!-- Grid Mata Kuliah Modern -->
    <div class="bg-white rounded-2xl p-6 shadow-lg ring-1 ring-slate-200 mb-6">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-800 mb-2">Pilih Mata Kuliah</h2>
            <p class="text-sm text-slate-600">Klik pada mata kuliah untuk mengisi form secara otomatis</p>
        </div>
        
        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <span class="i-heroicons-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></span>
                <input type="text" x-model="searchQuery"
                    placeholder="Cari mata kuliah..."
                    class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm text-slate-800"
                />
            </div>
        </div>

        <!-- Mata Kuliah Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto pr-2" style="scrollbar-width: thin;">
            <template x-for="mk in filteredMataKuliahList" :key="mk.kode_matakuliah || mk.id">
                <div @click="selectMataKuliah(mk)" 
                    :class="formData.kode_matakuliah == (mk.kode_matakuliah || mk.id) ? 'ring-2 ring-emerald-500 bg-emerald-50 border-emerald-300' : 'border-slate-200 hover:border-emerald-400 hover:shadow-md'"
                    class="border rounded-xl p-4 cursor-pointer transition-all duration-200 bg-white group">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800 text-sm mb-1.5 group-hover:text-emerald-700 transition-colors" x-text="mk.nama_matakuliah"></h3>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span x-show="mk.kode_matakuliah" class="text-xs font-bold text-emerald-600 bg-emerald-100 px-2.5 py-1 rounded-full" x-text="mk.kode_matakuliah"></span>
                                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
                                    <span x-text="mk.sks || 0"></span> SKS
                                </span>
                                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
                                    Semester <span x-text="mk.semester || '-'"></span>
                                </span>
                            </div>
                        </div>
                        <div :class="formData.kode_matakuliah == (mk.kode_matakuliah || mk.id) ? 'bg-emerald-600' : 'bg-slate-200 group-hover:bg-emerald-500'"
                            class="w-5 h-5 rounded-full flex items-center justify-center transition-colors">
                            <span x-show="formData.kode_matakuliah == (mk.kode_matakuliah || mk.id)" 
                                class="i-heroicons-check text-white text-xs"></span>
                        </div>
                    </div>
                </div>
            </template>
            <div x-show="filteredMataKuliahList.length === 0" class="col-span-full text-center py-12 text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                <span class="i-heroicons-academic-cap text-4xl text-slate-300 block mb-2"></span>
                <p class="text-sm font-medium">Tidak ada mata kuliah ditemukan</p>
            </div>
        </div>
    </div>

    <!-- Form RPS -->
    <div class="bg-white rounded-2xl p-8 shadow-lg ring-1 ring-slate-200" x-show="selectedMataKuliah">
        <form method="POST" action="#" @submit.prevent="submitForm()" class="space-y-6">
            @csrf
            <input type="hidden" name="kode_matakuliah" x-model="formData.kode_matakuliah" required>

            <!-- Form Detail Mata Kuliah -->
            <div class="space-y-6">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Detail Mata Kuliah</h3>
                    <p class="text-sm text-slate-600">Informasi mata kuliah yang dipilih</p>
                </div>
                
                <!-- Grid 2 Kolom untuk Field -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mata Kuliah (MK) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">1</span>
                            Mata Kuliah (MK)
                        </label>
                        <input type="text" name="nama_matakuliah" 
                            x-model="formData.nama_matakuliah"
                            readonly
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 bg-slate-50 shadow-sm cursor-not-allowed font-medium"
                        />
                    </div>

                    <!-- Kode -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">2</span>
                            Kode
                        </label>
                        <input type="text" name="kode" 
                            x-model="formData.kode"
                            readonly
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 bg-slate-50 shadow-sm cursor-not-allowed font-medium"
                        />
                    </div>

                    <!-- Bobot (SKS) -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">4</span>
                            Bobot (SKS)
                        </label>
                        <input type="text" name="sks" 
                            x-model="formData.sks"
                            readonly
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 bg-slate-50 shadow-sm cursor-not-allowed font-medium"
                        />
                    </div>

                    <!-- Semester -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">5</span>
                            Semester
                        </label>
                        <input type="text" name="semester_display" 
                            x-model="formData.semester_display"
                            readonly
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 bg-slate-50 shadow-sm cursor-not-allowed font-medium"
                        />
                    </div>

                    <!-- Tanggal Penyusunan -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">6</span>
                            Tanggal Penyusunan
                        </label>
                        <input type="text" name="tanggal_penyusunan" 
                            x-model="formData.tanggal_penyusunan"
                            readonly
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 bg-slate-50 shadow-sm cursor-not-allowed font-medium"
                        />
                    </div>

                    <!-- Bahan Kajian (BK) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">3</span>
                            Bahan Kajian (BK)
                        </label>
                        <textarea name="bahan_kajian" x-model="formData.bahan_kajian"
                            rows="4"
                            placeholder="Masukkan bahan kajian di sini..."
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm resize-none"
                        ></textarea>
                    </div>
                </div>
            </div>

            <!-- Otorisasi -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Otorisasi</h3>
                    <p class="text-sm text-slate-600">Informasi otorisasi dan persetujuan RPS</p>
                </div>

                <div class="space-y-6">
                    <!-- Dosen Pengembang RPS -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">1</span>
                            Dosen Pengembang RPS
                        </label>
                        <input type="text" name="dosen_pengembang" 
                            :value="dosenPengembang"
                            readonly
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 bg-slate-50 shadow-sm cursor-not-allowed font-medium"
                        />
                        <input type="hidden" name="dosen_pengembang_id" :value="dosenPengembangId">
                    </div>

                    <!-- Koordinasi BK -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">2</span>
                            Koordinasi BK <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="koordinasi_bk" x-model="formData.koordinasi_bk" required
                                class="w-full appearance-none rounded-xl border border-slate-300 pl-4 pr-10 py-3 text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm cursor-pointer">
                                <option value="">-- Pilih Koordinasi BK --</option>
                                <option value="adi_arga">Adi Arga Arifnur, M.Kom</option>
                                <option value="jefril_rahmadoni">Jefril Rahmadoni, M.Kom</option>
                                <option value="nisa_dwi">Nisa Dwi Angresti, M.Kom</option>
                                <option value="surya_afnarius">Prof. Ir. Surya Afnarius, MSc, PhD</option>
                            </select>
                            <span class="pointer-events-none i-heroicons-chevron-down-20-solid absolute right-3 top-1/2 -translate-y-1/2 text-emerald-600"></span>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Pilih dosen koordinasi BK yang sesuai</p>
                    </div>

                    <!-- Kaprodi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">3</span>
                            Kaprodi
                        </label>
                        <input type="text" name="kaprodi" 
                            value="Ricky Akbar, S.Kom, M.Kom"
                            readonly
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 bg-slate-50 shadow-sm cursor-not-allowed font-medium"
                        />
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="{{ route('fakultas.rps', ['code'=>$code]) }}?role={{ $userRole }}" 
                    class="px-6 py-3 rounded-xl border-2 border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 hover:border-slate-400 transition-all">
                    Batal
                </a>
                <button type="submit" 
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold shadow-lg hover:shadow-xl hover:from-emerald-700 hover:to-emerald-800 transition-all inline-flex items-center gap-2">
                    <span class="i-heroicons-check text-lg"></span>
                    Simpan RPS
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function inputRpsPage(allMataKuliah, semesters, dosenPengembang, dosenPengembangId) {
    return {
        allMataKuliah: allMataKuliah,
        semesters: semesters,
        searchQuery: '',
        dosenPengembang: dosenPengembang,
        dosenPengembangId: dosenPengembangId,
        formData: {
            semester: '',
            kode_matakuliah: '',
            nama_matakuliah: '',
            kode: '',
            bahan_kajian: '',
            sks: '',
            semester_display: '',
            tanggal_penyusunan: '',
            koordinasi_bk: ''
        },
        get filteredMataKuliahList() {
            let filtered = this.allMataKuliah;
            
            // Filter berdasarkan search query
            if (this.searchQuery.trim()) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(mk => 
                    (mk.nama_matakuliah && mk.nama_matakuliah.toLowerCase().includes(query)) ||
                    (mk.kode_matakuliah && mk.kode_matakuliah.toLowerCase().includes(query))
                );
            }
            
            // Sort by semester then nama
            return filtered.sort((a, b) => {
                if (a.semester !== b.semester) {
                    return (a.semester || 0) - (b.semester || 0);
                }
                return (a.nama_matakuliah || '').localeCompare(b.nama_matakuliah || '');
            });
        },
        get selectedMataKuliah() {
            if (!this.formData.kode_matakuliah) return null;
            return this.allMataKuliah.find(mk => 
                (mk.kode_matakuliah || mk.id) == this.formData.kode_matakuliah
            ) || null;
        },
        selectMataKuliah(mk) {
            this.formData.kode_matakuliah = mk.kode_matakuliah || mk.id;
            this.formData.semester = mk.semester || '';
            this.populateFormFields();
            
            // Scroll to form
            setTimeout(() => {
                document.querySelector('[x-show="selectedMataKuliah"]')?.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            }, 100);
        },
        populateFormFields() {
            // Auto-populate form fields ketika mata kuliah dipilih
            if (this.selectedMataKuliah) {
                const mk = this.selectedMataKuliah;
                
                // 1. Mata Kuliah (MK)
                this.formData.nama_matakuliah = mk.nama_matakuliah || '';
                
                // 2. Kode
                this.formData.kode = mk.kode_matakuliah || mk.id || '';
                
                // 3. Bahan Kajian (BK) - kosongkan saja
                this.formData.bahan_kajian = '';
                
                // 4. Bobot (SKS)
                this.formData.sks = (mk.sks || 0) + ' SKS';
                
                // 5. Semester
                this.formData.semester_display = 'Semester ' + (mk.semester || '-');
                
                // 6. Tanggal Penyusunan (real time)
                const now = new Date();
                const options = { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                this.formData.tanggal_penyusunan = now.toLocaleDateString('id-ID', options);
            } else {
                // Reset semua field jika tidak ada mata kuliah yang dipilih
                this.formData.nama_matakuliah = '';
                this.formData.kode = '';
                this.formData.bahan_kajian = '';
                this.formData.sks = '';
                this.formData.semester_display = '';
                this.formData.tanggal_penyusunan = '';
            }
        },
        init() {
            // Pre-select mata kuliah dari URL parameter jika ada
            const urlParams = new URLSearchParams(window.location.search);
            const kodeParam = urlParams.get('kode');
            const semesterParam = urlParams.get('semester');
            
            setTimeout(() => {
                if (kodeParam) {
                    // Find mata kuliah by kode
                    const mk = this.allMataKuliah.find(m => 
                        (m.kode_matakuliah == kodeParam || m.id == kodeParam)
                    );
                    if (mk) {
                        this.selectMataKuliah(mk);
                    }
                }
            }, 200);
        },
        submitForm() {
            // Validasi
            if (!this.formData.kode_matakuliah) {
                alert('Pilih mata kuliah terlebih dahulu');
                return false;
            }

            if (!this.formData.koordinasi_bk) {
                alert('Pilih Koordinasi BK terlebih dahulu');
                return false;
            }

            // Form akan submit secara normal
            return true;
        }
    };
}
</script>
@endsection

