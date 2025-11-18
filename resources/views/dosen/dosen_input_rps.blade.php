@extends('layouts.app')

@section('content')
<!-- Alpine.js Check -->
<div x-data="{ loaded: true }" x-init="console.log('Alpine.js is working!')" style="display: none;"></div>

<div x-data='inputRpsPage(@json($allMataKuliah), @json($semesters), @json($dosenPengembang), {{ $dosenPengembangId ?? 'null' }}, @json($cplCodes ?? []), @json($cplDescriptions ?? []), @json($ikCodes ?? []), @json($ikDescriptions ?? []), @json($asesmenModels ?? []), @json($rpsData ?? null))' class="space-y-6 mt-4" @init="init()">
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
            <h2 class="text-xl font-bold text-slate-800 mb-2">
                Pilih Mata Kuliah 
                <span class="text-sm font-normal text-slate-500" x-text="'(' + (allMataKuliah?.length || 0) + ' tersedia)'"></span>
            </h2>
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

        <!-- Debug Info -->
        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs" x-show="allMataKuliah.length === 0">
            <p class="text-blue-800">⚠️ Debug: Tidak ada data mata kuliah. Total: <span x-text="allMataKuliah.length"></span></p>
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
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <span class="i-heroicons-exclamation-circle text-red-600 text-xl mt-0.5"></span>
                    <div class="flex-1">
                        <h4 class="font-semibold text-red-800 mb-2">Terjadi Kesalahan:</h4>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <span class="i-heroicons-exclamation-circle text-red-600 text-xl mt-0.5"></span>
                    <div class="flex-1">
                        <h4 class="font-semibold text-red-800">{{ session('error') }}</h4>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('rps.store', ['code' => $code]) }}" class="space-y-6" 
              x-on:submit="
                  console.log('Submitting - SKS:', formData.sks_numeric, 'type:', typeof formData.sks_numeric);
                  console.log('Submitting - Semester:', formData.semester, 'type:', typeof formData.semester);
              ">
            @csrf
            <input type="hidden" name="rps_id" :value="rpsData ? rpsData.rps_id : ''">
            <input type="hidden" name="kode_matakuliah" x-model="formData.kode_matakuliah" required>
            <input type="hidden" name="nama_matakuliah" x-model="formData.nama_matakuliah">
            <input type="hidden" name="dosen_pengembang" :value="dosenPengembang">
            <input type="hidden" name="dosen_pengembang_id" :value="dosenPengembangId">
            <input type="hidden" name="semester" :value="Number(formData.semester) || 0">
            <input type="hidden" name="sks" :value="Number(formData.sks_numeric) || 0">
            <input type="hidden" name="tanggal_penyusunan" x-model="formData.tanggal_penyusunan">

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
                        <input type="text"
                            x-model="formData.tanggal_penyusunan_display"
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
                            Dosen Pengembang RPS <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="dosen_pengembang"
                            x-model="formData.dosen_pengembang"
                            placeholder="Masukkan nama dosen pengembang RPS"
                            required
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm"
                        />
                        <p class="mt-1 text-xs text-slate-500">Contoh: Dr. Nama Dosen, S.Kom, M.Kom</p>
                    </div>

                    <!-- Koordinasi BK -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">2</span>
                            Koordinasi BK <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="koordinasi_bk"
                            x-model="formData.koordinasi_bk"
                            placeholder="Masukkan nama dosen koordinasi BK"
                            required
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm"
                        />
                        <p class="mt-1 text-xs text-slate-500">Contoh: Adi Arga Arifnur, M.Kom</p>
                    </div>

                    <!-- Kaprodi -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">3</span>
                            Kaprodi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kaprodi"
                            x-model="formData.kaprodi"
                            placeholder="Masukkan nama Kaprodi"
                            required
                            class="w-full rounded-xl border border-slate-300 pl-4 pr-4 py-3 text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm"
                        />
                        <p class="mt-1 text-xs text-slate-500">Contoh: Ricky Akbar, S.Kom, M.Kom</p>
                    </div>
                </div>
            </div>

            <!-- Capaian Pembelajaran -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Capaian Pembelajaran</h3>
                    <p class="text-sm text-slate-600">Tentukan capaian pembelajaran dan indikator untuk mata kuliah ini</p>
                </div>

                <div class="space-y-8">
                    <!-- 1. CPL-PRODI -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">1</span>
                            CPL-PRODI yang dibebankan pada MK <span class="text-red-500">*</span>
                        </label>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 space-y-3">
                            <template x-for="(code, index) in cplCodes" :key="code">
                                <label class="flex items-start gap-3 p-3 rounded-lg hover:bg-white transition-colors cursor-pointer group">
                                    <input type="checkbox" 
                                        :name="'cpl_prodi[]'" 
                                        :value="code"
                                        x-model="formData.selectedCpl"
                                        class="mt-1 w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-emerald-700 text-sm" x-text="code"></span>
                                        </div>
                                        <p class="text-sm text-slate-700 leading-relaxed" x-text="cplDescriptions[code]"></p>
                                    </div>
                                </label>
                            </template>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Pilih satu atau lebih CPL-PRODI yang relevan</p>
                    </div>

                    <!-- 2. Indikator (IK) -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">2</span>
                            Indikator (IK) <span class="text-red-500">*</span>
                        </label>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 space-y-3">
                            <template x-for="(code, index) in ikCodes" :key="code">
                                <label class="flex items-start gap-3 p-3 rounded-lg hover:bg-white transition-colors cursor-pointer group">
                                    <input type="checkbox" 
                                        :name="'indikator[]'" 
                                        :value="code"
                                        x-model="formData.selectedIk"
                                        class="mt-1 w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-emerald-700 text-sm" x-text="code"></span>
                                        </div>
                                        <p class="text-sm text-slate-700 leading-relaxed" x-text="ikDescriptions[code]"></p>
                                    </div>
                                </label>
                            </template>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Pilih satu atau lebih Indikator yang relevan</p>
                    </div>

                    <!-- 3. CPMK -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">3</span>
                            Kemampuan Akhir Tiap Tahapan Belajar (CPMK) <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-4">
                            <template x-for="(cpmk, index) in formData.cpmkList" :key="index">
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-semibold text-slate-800 text-sm">CPMK-<span x-text="index + 1"></span></h4>
                                        <button type="button" 
                                            @click="removeCpmk(index)"
                                            x-show="formData.cpmkList.length > 1"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                            <span class="i-heroicons-trash text-lg"></span>
                                        </button>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">Deskripsi CPMK</label>
                                        <textarea 
                                            :name="'cpmk_deskripsi[]'" 
                                            x-model="cpmk.deskripsi"
                                            rows="3"
                                            placeholder="Masukkan deskripsi kemampuan akhir yang diharapkan..."
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                        <!-- Hidden input untuk kode CPMK otomatis -->
                                        <input type="hidden" :name="'cpmk_kode[]'" :value="'CPMK-' + (index + 1)">
                                    </div>
                                </div>
                            </template>
                            <button type="button" 
                                @click="addCpmk()"
                                class="w-full py-3 rounded-xl border-2 border-dashed border-emerald-300 text-emerald-700 font-semibold hover:bg-emerald-50 hover:border-emerald-400 transition-all inline-flex items-center justify-center gap-2">
                                <span class="i-heroicons-plus-circle text-xl"></span>
                                Tambah CPMK
                            </button>
                        </div>
                    </div>

                    <!-- 4. Korelasi CPMK terhadap CPL -->
                    <div x-show="formData.cpmkList.length > 0 && (formData.selectedCpl.length > 0 || formData.selectedIk.length > 0)">
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">4</span>
                            Korelasi CPMK terhadap CPL
                        </label>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-slate-300">
                                        <th class="px-3 py-3 text-left font-semibold text-slate-800 bg-emerald-50 rounded-tl-lg">CPMK</th>
                                        <template x-for="cplCode in formData.selectedCpl" :key="'cpl-header-' + cplCode">
                                            <th class="px-3 py-3 text-center font-semibold text-slate-800 bg-emerald-50" x-text="cplCode"></th>
                                        </template>
                                        <template x-for="ikCode in formData.selectedIk" :key="'ik-header-' + ikCode">
                                            <th class="px-3 py-3 text-center font-semibold text-slate-800 bg-blue-50" x-text="ikCode"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(cpmk, cpmkIndex) in formData.cpmkList" :key="'cpmk-row-' + cpmkIndex">
                                        <tr class="border-b border-slate-200 hover:bg-white transition-colors">
                                            <td class="px-3 py-3 font-medium text-slate-800" x-text="'CPMK-' + (cpmkIndex + 1)"></td>
                                            <template x-for="cplCode in formData.selectedCpl" :key="'cpl-cell-' + cpmkIndex + '-' + cplCode">
                                                <td class="px-3 py-3 text-center">
                                                    <input type="checkbox" 
                                                        :name="'korelasi[' + cpmkIndex + '][cpl][]'" 
                                                        :value="cplCode"
                                                        x-model="formData.korelasi[cpmkIndex].cpl"
                                                        class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                                                </td>
                                            </template>
                                            <template x-for="ikCode in formData.selectedIk" :key="'ik-cell-' + cpmkIndex + '-' + ikCode">
                                                <td class="px-3 py-3 text-center">
                                                    <input type="checkbox" 
                                                        :name="'korelasi[' + cpmkIndex + '][ik][]'" 
                                                        :value="ikCode"
                                                        x-model="formData.korelasi[cpmkIndex].ik"
                                                        class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500 cursor-pointer">
                                                </td>
                                            </template>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <p class="mt-2 text-xs text-slate-500">Centang untuk menandai korelasi antara CPMK dengan CPL dan IK</p>
                    </div>

                    <!-- 5. Komponen Asesmen -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">5</span>
                            Komponen Asesmen <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-4">
                            <template x-for="(asesmen, index) in formData.asesmenList" :key="index">
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="font-semibold text-slate-800 text-sm">Asesmen <span x-text="index + 1"></span></h4>
                                        <button type="button" 
                                            @click="removeAsesmen(index)"
                                            x-show="formData.asesmenList.length > 1"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                            <span class="i-heroicons-trash text-lg"></span>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 mb-1">Jenis Asesmen</label>
                                            <div class="relative">
                                                <select 
                                                    :name="'asesmen_jenis[]'" 
                                                    x-model="asesmen.jenis"
                                                    class="w-full appearance-none rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white cursor-pointer">
                                                    <option value="">-- Pilih --</option>
                                                    <template x-for="model in asesmenModels" :key="model">
                                                        <option :value="model" x-text="model"></option>
                                                    </template>
                                                </select>
                                                <span class="pointer-events-none i-heroicons-chevron-down-20-solid absolute right-2 top-1/2 -translate-y-1/2 text-emerald-600 text-sm"></span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 mb-1">Bobot (%)</label>
                                            <input type="number" 
                                                :name="'asesmen_bobot[]'" 
                                                x-model="asesmen.bobot"
                                                min="0"
                                                max="100"
                                                placeholder="0-100"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-slate-700 mb-1">Keterangan</label>
                                            <input type="text" 
                                                :name="'asesmen_keterangan[]'" 
                                                x-model="asesmen.keterangan"
                                                placeholder="Keterangan tambahan"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <button type="button" 
                                @click="addAsesmen()"
                                class="w-full py-3 rounded-xl border-2 border-dashed border-emerald-300 text-emerald-700 font-semibold hover:bg-emerald-50 hover:border-emerald-400 transition-all inline-flex items-center justify-center gap-2">
                                <span class="i-heroicons-plus-circle text-xl"></span>
                                Tambah Komponen Asesmen
                            </button>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start gap-2">
                                <span class="i-heroicons-information-circle text-blue-600 text-lg mt-0.5"></span>
                                <div class="flex-1">
                                    <p class="text-xs text-blue-800 font-medium">Total Bobot: <span x-text="totalBobot"></span>%</p>
                                    <p class="text-xs text-blue-700 mt-1">Pastikan total bobot sama dengan 100%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Deskripsi Singkat MK -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Deskripsi Singkat MK</h3>
                    <p class="text-sm text-slate-600">Deskripsi singkat mengenai mata kuliah ini</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">1</span>
                        Deskripsi Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi_mk" x-model="formData.deskripsi_mk"
                        rows="5"
                        placeholder="Masukkan deskripsi singkat mata kuliah..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm resize-none"
                    ></textarea>
                    <p class="mt-2 text-xs text-slate-500">Jelaskan secara singkat tentang mata kuliah ini</p>
                </div>
            </div>

            <!-- Bahan Kajian: Materi Pembelajaran -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Bahan Kajian: Materi Pembelajaran</h3>
                    <p class="text-sm text-slate-600">Daftar materi pembelajaran yang akan dibahas</p>
                </div>

                <div class="space-y-4">
                    <template x-for="(materi, index) in formData.materiList" :key="index">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold text-sm flex items-center justify-center mt-1">
                                    <span x-text="index + 1"></span>
                                </div>
                                <div class="flex-1">
                                    <textarea 
                                        :name="'materi_pembelajaran[]'" 
                                        x-model="materi.isi"
                                        rows="2"
                                        placeholder="Masukkan materi pembelajaran..."
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                </div>
                                <button type="button" 
                                    @click="removeMateri(index)"
                                    x-show="formData.materiList.length > 1"
                                    class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                    <span class="i-heroicons-trash text-lg"></span>
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" 
                        @click="addMateri()"
                        class="w-full py-3 rounded-xl border-2 border-dashed border-emerald-300 text-emerald-700 font-semibold hover:bg-emerald-50 hover:border-emerald-400 transition-all inline-flex items-center justify-center gap-2">
                        <span class="i-heroicons-plus-circle text-xl"></span>
                        Tambah Materi Pembelajaran
                    </button>
                </div>
            </div>

            <!-- Pustaka -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Pustaka</h3>
                    <p class="text-sm text-slate-600">Daftar referensi dan pustaka yang digunakan</p>
                </div>

                <div class="space-y-6">
                    <!-- Pustaka Utama -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">a</span>
                            Pustaka Utama <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <template x-for="(pustaka, index) in formData.pustakaUtamaList" :key="index">
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold text-sm flex items-center justify-center mt-1">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" 
                                                :name="'pustaka_utama[]'" 
                                                x-model="pustaka.isi"
                                                placeholder="Contoh: Penulis. (Tahun). Judul Buku. Penerbit."
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                        </div>
                                        <button type="button" 
                                            @click="removePustakaUtama(index)"
                                            x-show="formData.pustakaUtamaList.length > 1"
                                            class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                            <span class="i-heroicons-trash text-lg"></span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <button type="button" 
                                @click="addPustakaUtama()"
                                class="w-full py-3 rounded-xl border-2 border-dashed border-blue-300 text-blue-700 font-semibold hover:bg-blue-50 hover:border-blue-400 transition-all inline-flex items-center justify-center gap-2">
                                <span class="i-heroicons-plus-circle text-xl"></span>
                                Tambah Pustaka Utama
                            </button>
                        </div>
                    </div>

                    <!-- Pustaka Pendukung -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">b</span>
                            Pustaka Pendukung
                        </label>
                        <div class="space-y-3">
                            <template x-for="(pustaka, index) in formData.pustakaPendukungList" :key="index">
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold text-sm flex items-center justify-center mt-1">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" 
                                                :name="'pustaka_pendukung[]'" 
                                                x-model="pustaka.isi"
                                                placeholder="Contoh: Penulis. (Tahun). Judul Buku. Penerbit."
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                        </div>
                                        <button type="button" 
                                            @click="removePustakaPendukung(index)"
                                            x-show="formData.pustakaPendukungList.length > 1"
                                            class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                            <span class="i-heroicons-trash text-lg"></span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <button type="button" 
                                @click="addPustakaPendukung()"
                                class="w-full py-3 rounded-xl border-2 border-dashed border-purple-300 text-purple-700 font-semibold hover:bg-purple-50 hover:border-purple-400 transition-all inline-flex items-center justify-center gap-2">
                                <span class="i-heroicons-plus-circle text-xl"></span>
                                Tambah Pustaka Pendukung
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Pembelajaran -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Media Pembelajaran</h3>
                    <p class="text-sm text-slate-600">Perangkat dan media yang digunakan dalam pembelajaran</p>
                </div>

                <div class="space-y-6">
                    <!-- Perangkat Lunak -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">a</span>
                            Perangkat Lunak (Software)
                        </label>
                        <div class="space-y-3">
                            <template x-for="(software, index) in formData.perangkatLunakList" :key="index">
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-cyan-100 text-cyan-700 font-bold text-sm flex items-center justify-center mt-1">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" 
                                                :name="'perangkat_lunak[]'" 
                                                x-model="software.isi"
                                                placeholder="Contoh: Microsoft Visual Studio Code, MySQL Workbench"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                        </div>
                                        <button type="button" 
                                            @click="removePerangkatLunak(index)"
                                            x-show="formData.perangkatLunakList.length > 1"
                                            class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                            <span class="i-heroicons-trash text-lg"></span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <button type="button" 
                                @click="addPerangkatLunak()"
                                class="w-full py-3 rounded-xl border-2 border-dashed border-cyan-300 text-cyan-700 font-semibold hover:bg-cyan-50 hover:border-cyan-400 transition-all inline-flex items-center justify-center gap-2">
                                <span class="i-heroicons-plus-circle text-xl"></span>
                                Tambah Perangkat Lunak
                            </button>
                        </div>
                    </div>

                    <!-- Perangkat Keras -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-800 mb-3">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold mr-2">b</span>
                            Perangkat Keras (Hardware)
                        </label>
                        <div class="space-y-3">
                            <template x-for="(hardware, index) in formData.perangkatKerasList" :key="index">
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-orange-100 text-orange-700 font-bold text-sm flex items-center justify-center mt-1">
                                            <span x-text="index + 1"></span>
                                        </div>
                                        <div class="flex-1">
                                            <input type="text" 
                                                :name="'perangkat_keras[]'" 
                                                x-model="hardware.isi"
                                                placeholder="Contoh: Komputer/Laptop, Proyektor, Whiteboard"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                        </div>
                                        <button type="button" 
                                            @click="removePerangkatKeras(index)"
                                            x-show="formData.perangkatKerasList.length > 1"
                                            class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                            <span class="i-heroicons-trash text-lg"></span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <button type="button" 
                                @click="addPerangkatKeras()"
                                class="w-full py-3 rounded-xl border-2 border-dashed border-orange-300 text-orange-700 font-semibold hover:bg-orange-50 hover:border-orange-400 transition-all inline-flex items-center justify-center gap-2">
                                <span class="i-heroicons-plus-circle text-xl"></span>
                                Tambah Perangkat Keras
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dosen Pengampu -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Dosen Pengampu</h3>
                    <p class="text-sm text-slate-600">Daftar dosen yang mengampu mata kuliah ini</p>
                </div>

                <div class="space-y-4">
                    <template x-for="(dosen, index) in formData.dosenPengampuList" :key="index">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold text-sm flex items-center justify-center mt-1">
                                    <span x-text="index + 1"></span>
                                </div>
                                <div class="flex-1">
                                    <input type="text" 
                                        :name="'dosen_pengampu[]'" 
                                        x-model="dosen.nama"
                                        placeholder="Contoh: Dr. Nama Dosen, S.Kom, M.Kom"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                </div>
                                <button type="button" 
                                    @click="removeDosenPengampu(index)"
                                    x-show="formData.dosenPengampuList.length > 1"
                                    class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                    <span class="i-heroicons-trash text-lg"></span>
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" 
                        @click="addDosenPengampu()"
                        class="w-full py-3 rounded-xl border-2 border-dashed border-indigo-300 text-indigo-700 font-semibold hover:bg-indigo-50 hover:border-indigo-400 transition-all inline-flex items-center justify-center gap-2">
                        <span class="i-heroicons-plus-circle text-xl"></span>
                        Tambah Dosen Pengampu
                    </button>
                </div>
            </div>

            <!-- Mata Kuliah Prasyarat -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Mata Kuliah Prasyarat</h3>
                    <p class="text-sm text-slate-600">Mata kuliah yang harus diselesaikan sebelum mengambil mata kuliah ini</p>
                </div>

                <div class="space-y-4">
                    <template x-for="(prasyarat, index) in formData.mkPrasyaratList" :key="index">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-pink-100 text-pink-700 font-bold text-sm flex items-center justify-center mt-1">
                                    <span x-text="index + 1"></span>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Nama MK</label>
                                    <input type="text" 
                                        :name="'mk_prasyarat_nama[]'" 
                                        x-model="prasyarat.nama"
                                        placeholder="Contoh: Pengantar Sistem Informasi"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                </div>
                                <button type="button" 
                                    @click="removeMkPrasyarat(index)"
                                    x-show="formData.mkPrasyaratList.length > 1"
                                    class="flex-shrink-0 text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                    <span class="i-heroicons-trash text-lg"></span>
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" 
                        @click="addMkPrasyarat()"
                        class="w-full py-3 rounded-xl border-2 border-dashed border-pink-300 text-pink-700 font-semibold hover:bg-pink-50 hover:border-pink-400 transition-all inline-flex items-center justify-center gap-2">
                        <span class="i-heroicons-plus-circle text-xl"></span>
                        Tambah Mata Kuliah Prasyarat
                    </button>
                    <p class="text-xs text-slate-500 mt-2">Kosongkan jika tidak ada mata kuliah prasyarat</p>
                </div>
            </div>

            <!-- Rencana Pembelajaran Semester (RPS) -->
            <div class="pt-6 border-t border-slate-200">
                <div class="mb-6 pb-4 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 mb-1">Rencana Pembelajaran Semester (RPS)</h3>
                    <p class="text-sm text-slate-600">Rencana pembelajaran per minggu dengan detail aktivitas, materi, dan penilaian</p>
                </div>

                <div class="space-y-6">
                    <template x-for="(aktivitas, index) in formData.aktivitasPembelajaranList" :key="index">
                        <div class="bg-slate-50 rounded-xl p-6 border-2 border-slate-200 hover:border-emerald-300 transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-lg text-slate-800">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 text-sm font-bold mr-2">
                                        <span x-text="index + 1"></span>
                                    </span>
                                    Minggu ke-<span x-text="index + 1"></span>
                                </h4>
                                <button type="button" 
                                    @click="removeAktivitasPembelajaran(index)"
                                    x-show="formData.aktivitasPembelajaranList.length > 1"
                                    class="text-red-600 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                    <span class="i-heroicons-trash text-lg"></span>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <!-- Minggu ke- -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                                        Minggu ke- <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                        :name="'aktivitas_minggu[]'" 
                                        x-model="aktivitas.minggu_ke"
                                        placeholder="Contoh: 1-2, 3-4, UTS, UAS"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                </div>

                                <!-- CPMK -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                                        CPMK <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select 
                                            :name="'aktivitas_cpmk[]'" 
                                            x-model="aktivitas.cpmk_kode"
                                            required
                                            class="w-full appearance-none rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white cursor-pointer">
                                            <option value="">-- Pilih CPMK --</option>
                                            <template x-for="(cpmk, cpmkIndex) in formData.cpmkList" :key="cpmkIndex">
                                                <option :value="'CPMK-' + (cpmkIndex + 1)" 
                                                    x-text="'CPMK-' + (cpmkIndex + 1)"></option>
                                            </template>
                                        </select>
                                        <span class="pointer-events-none i-heroicons-chevron-down-20-solid absolute right-2 top-1/2 -translate-y-1/2 text-emerald-600 text-sm"></span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500">Pilih CPMK yang telah didefinisikan sebelumnya</p>
                                </div>

                                <!-- Indikator Penilaian -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                                        Indikator Penilaian
                                    </label>
                                    <textarea 
                                        :name="'aktivitas_indikator_penilaian[]'" 
                                        x-model="aktivitas.indikator_penilaian"
                                        rows="2"
                                        placeholder="Masukkan indikator penilaian..."
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                </div>

                                <!-- Bentuk Penilaian -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                                            Bentuk Penilaian (Jenis)
                                        </label>
                                        <input type="text" 
                                            :name="'aktivitas_bentuk_penilaian_jenis[]'" 
                                            x-model="aktivitas.bentuk_penilaian_jenis"
                                            placeholder="Contoh: Quiz, Proposal, UTS, UAS"
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-800 mb-2">
                                            Bentuk Penilaian (Bobot %)
                                        </label>
                                        <input type="number" 
                                            :name="'aktivitas_bentuk_penilaian_bobot[]'" 
                                            x-model="aktivitas.bentuk_penilaian_bobot"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            placeholder="0-100"
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white">
                                    </div>
                                </div>

                                <!-- Aktivitas Pembelajaran -->
                                <div class="bg-white rounded-lg p-4 border border-slate-200">
                                    <label class="block text-sm font-semibold text-slate-800 mb-3">
                                        Aktivitas Pembelajaran
                                    </label>
                                    
                                    <!-- Sinkronous -->
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-700 mb-2">Sinkronous</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-slate-600 mb-1">Luring (Offline)</label>
                                                <textarea 
                                                    :name="'aktivitas_sinkron_luring[]'" 
                                                    x-model="aktivitas.aktivitas_sinkron_luring"
                                                    rows="2"
                                                    placeholder="Contoh: Kuliah, diskusi, presentasi"
                                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-slate-600 mb-1">Daring (Online)</label>
                                                <textarea 
                                                    :name="'aktivitas_sinkron_daring[]'" 
                                                    x-model="aktivitas.aktivitas_sinkron_daring"
                                                    rows="2"
                                                    placeholder="Contoh: Video conference, live streaming"
                                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Asinkronous -->
                                    <div class="mb-4">
                                        <label class="block text-xs font-semibold text-slate-700 mb-2">Asinkronous</label>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-slate-600 mb-1">Mandiri (Independent)</label>
                                                <textarea 
                                                    :name="'aktivitas_asinkron_mandiri[]'" 
                                                    x-model="aktivitas.aktivitas_asinkron_mandiri"
                                                    rows="2"
                                                    placeholder="Contoh: Membaca materi, mengerjakan tugas"
                                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-slate-600 mb-1">Kolaboratif (Collaborative)</label>
                                                <textarea 
                                                    :name="'aktivitas_asinkron_kolaboratif[]'" 
                                                    x-model="aktivitas.aktivitas_asinkron_kolaboratif"
                                                    rows="2"
                                                    placeholder="Contoh: Diskusi kelompok, presentasi kelompok"
                                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Media -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-2">Media</label>
                                        <textarea 
                                            :name="'aktivitas_media[]'" 
                                            x-model="aktivitas.media"
                                            rows="2"
                                            placeholder="Contoh: Ms. Powerpoint, Ms. Teams, Classroom, Projector, Sparx System"
                                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                    </div>
                                </div>

                                <!-- Materi Pembelajaran -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                                        Materi Pembelajaran
                                    </label>
                                    <textarea 
                                        :name="'aktivitas_materi_pembelajaran[]'" 
                                        x-model="aktivitas.materi_pembelajaran"
                                        rows="3"
                                        placeholder="Masukkan materi pembelajaran yang akan dibahas..."
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                </div>

                                <!-- Referensi -->
                                <div>
                                    <label class="block text-sm font-semibold text-slate-800 mb-2">
                                        Referensi
                                    </label>
                                    <textarea 
                                        :name="'aktivitas_referensi[]'" 
                                        x-model="aktivitas.referensi"
                                        rows="2"
                                        placeholder="Masukkan referensi yang digunakan (nomor referensi atau deskripsi)..."
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>

                    <button type="button" 
                        @click="addAktivitasPembelajaran()"
                        class="w-full py-3 rounded-xl border-2 border-dashed border-emerald-300 text-emerald-700 font-semibold hover:bg-emerald-50 hover:border-emerald-400 transition-all inline-flex items-center justify-center gap-2">
                        <span class="i-heroicons-plus-circle text-xl"></span>
                        Tambah Aktivitas Pembelajaran
                    </button>
                    <p class="text-xs text-slate-500 mt-2">Tambahkan aktivitas pembelajaran untuk setiap minggu atau periode (UTS/UAS)</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="{{ route('fakultas.rps', ['code'=>$code]) }}?role={{ $userRole }}" 
                    class="px-6 py-3 rounded-xl border-2 border-slate-300 text-slate-700 font-semibold hover:bg-slate-50 hover:border-slate-400 transition-all">
                    Batal
                </a>
                <button type="submit" 
                    @click="console.log('Form submitted', formData)"
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 text-white font-semibold shadow-lg hover:shadow-xl hover:from-emerald-700 hover:to-emerald-800 transition-all inline-flex items-center gap-2">
                    <span class="i-heroicons-check text-lg"></span>
                    Simpan RPS
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function inputRpsPage(allMataKuliah, semesters, dosenPengembang, dosenPengembangId, cplCodes, cplDescriptions, ikCodes, ikDescriptions, asesmenModels, rpsData) {
    console.log('RPS Page Initialized');
    console.log('Total Mata Kuliah:', allMataKuliah ? allMataKuliah.length : 0);
    console.log('RPS Data:', rpsData);
    
    return {
        allMataKuliah: allMataKuliah || [],
        semesters: semesters || [],
        searchQuery: '',
        dosenPengembang: dosenPengembang || '',
        dosenPengembangId: dosenPengembangId || null,
        cplCodes: cplCodes || [],
        cplDescriptions: cplDescriptions || {},
        ikCodes: ikCodes || [],
        ikDescriptions: ikDescriptions || {},
        asesmenModels: asesmenModels || [],
        rpsData: rpsData || null,
        formData: {
            semester: 0,
            kode_matakuliah: '',
            nama_matakuliah: '',
            kode: '',
            bahan_kajian: '',
            sks: '',
            sks_numeric: 0,
            semester_display: '',
            tanggal_penyusunan: '',
            tanggal_penyusunan_display: '',
            dosen_pengembang: '',
            koordinasi_bk: '',
            kaprodi: '',
            selectedCpl: [],
            selectedIk: [],
            cpmkList: [
                { deskripsi: '' }
            ],
            korelasi: [
                { cpl: [], ik: [] }
            ],
            asesmenList: [
                { jenis: '', bobot: '', keterangan: '' }
            ],
            deskripsi_mk: '',
            materiList: [
                { isi: '' }
            ],
            pustakaUtamaList: [
                { isi: '' }
            ],
            pustakaPendukungList: [
                { isi: '' }
            ],
            perangkatLunakList: [
                { isi: '' }
            ],
            perangkatKerasList: [
                { isi: '' }
            ],
            dosenPengampuList: [
                { nama: '' }
            ],
            mkPrasyaratList: [
                { nama: '' }
            ],
            aktivitasPembelajaranList: [
                {
                    minggu_ke: '',
                    cpmk_kode: '',
                    indikator_penilaian: '',
                    bentuk_penilaian_jenis: '',
                    bentuk_penilaian_bobot: '',
                    aktivitas_sinkron_luring: '',
                    aktivitas_sinkron_daring: '',
                    aktivitas_asinkron_mandiri: '',
                    aktivitas_asinkron_kolaboratif: '',
                    media: '',
                    materi_pembelajaran: '',
                    referensi: ''
                }
            ]
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
                
                // 4. Bobot (SKS) - for display and numeric
                this.formData.sks_numeric = parseInt(mk.sks, 10) || 0;
                this.formData.sks = (mk.sks || 0) + ' SKS';
                console.log('Populated SKS:', this.formData.sks_numeric, 'type:', typeof this.formData.sks_numeric);
                
                // 5. Semester - for display and numeric
                this.formData.semester = parseInt(mk.semester, 10) || 0;
                this.formData.semester_display = 'Semester ' + (mk.semester || '-');
                console.log('Populated Semester:', this.formData.semester, 'type:', typeof this.formData.semester);
                
                // 6. Tanggal Penyusunan (real time)
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                const seconds = String(now.getSeconds()).padStart(2, '0');
                
                // Format MySQL untuk database (Y-m-d H:i:s)
                this.formData.tanggal_penyusunan = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                
                // Format Indonesia untuk display
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                   'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                this.formData.tanggal_penyusunan_display = `${day} ${monthNames[now.getMonth()]} ${year} pukul ${hours}:${minutes}`;
                
                console.log('Tanggal MySQL:', this.formData.tanggal_penyusunan);
                console.log('Tanggal Display:', this.formData.tanggal_penyusunan_display);
            } else {
                // Reset semua field jika tidak ada mata kuliah yang dipilih
                this.formData.nama_matakuliah = '';
                this.formData.kode = '';
                this.formData.bahan_kajian = '';
                this.formData.sks = '';
                this.formData.sks_numeric = 0;
                this.formData.semester = 0;
                this.formData.semester_display = '';
                this.formData.tanggal_penyusunan = '';
                this.formData.tanggal_penyusunan_display = '';
            }
        },
        get totalBobot() {
            return this.formData.asesmenList.reduce((total, asesmen) => {
                return total + (parseFloat(asesmen.bobot) || 0);
            }, 0);
        },
        addCpmk() {
            this.formData.cpmkList.push({ deskripsi: '' });
            this.formData.korelasi.push({ cpl: [], ik: [] });
        },
        removeCpmk(index) {
            if (this.formData.cpmkList.length > 1) {
                this.formData.cpmkList.splice(index, 1);
                this.formData.korelasi.splice(index, 1);
            }
        },
        addAsesmen() {
            this.formData.asesmenList.push({ jenis: '', bobot: '', keterangan: '' });
        },
        removeAsesmen(index) {
            if (this.formData.asesmenList.length > 1) {
                this.formData.asesmenList.splice(index, 1);
            }
        },
        addMateri() {
            this.formData.materiList.push({ isi: '' });
        },
        removeMateri(index) {
            if (this.formData.materiList.length > 1) {
                this.formData.materiList.splice(index, 1);
            }
        },
        addPustakaUtama() {
            this.formData.pustakaUtamaList.push({ isi: '' });
        },
        removePustakaUtama(index) {
            if (this.formData.pustakaUtamaList.length > 1) {
                this.formData.pustakaUtamaList.splice(index, 1);
            }
        },
        addPustakaPendukung() {
            this.formData.pustakaPendukungList.push({ isi: '' });
        },
        removePustakaPendukung(index) {
            if (this.formData.pustakaPendukungList.length > 1) {
                this.formData.pustakaPendukungList.splice(index, 1);
            }
        },
        addPerangkatLunak() {
            this.formData.perangkatLunakList.push({ isi: '' });
        },
        removePerangkatLunak(index) {
            if (this.formData.perangkatLunakList.length > 1) {
                this.formData.perangkatLunakList.splice(index, 1);
            }
        },
        addPerangkatKeras() {
            this.formData.perangkatKerasList.push({ isi: '' });
        },
        removePerangkatKeras(index) {
            if (this.formData.perangkatKerasList.length > 1) {
                this.formData.perangkatKerasList.splice(index, 1);
            }
        },
        addDosenPengampu() {
            this.formData.dosenPengampuList.push({ nama: '' });
        },
        removeDosenPengampu(index) {
            if (this.formData.dosenPengampuList.length > 1) {
                this.formData.dosenPengampuList.splice(index, 1);
            }
        },
        addMkPrasyarat() {
            this.formData.mkPrasyaratList.push({ nama: '' });
        },
        removeMkPrasyarat(index) {
            if (this.formData.mkPrasyaratList.length > 1) {
                this.formData.mkPrasyaratList.splice(index, 1);
            }
        },
        addAktivitasPembelajaran() {
            this.formData.aktivitasPembelajaranList.push({
                minggu_ke: '',
                cpmk_kode: '',
                indikator_penilaian: '',
                bentuk_penilaian_jenis: '',
                bentuk_penilaian_bobot: '',
                aktivitas_sinkron_luring: '',
                aktivitas_sinkron_daring: '',
                aktivitas_asinkron_mandiri: '',
                aktivitas_asinkron_kolaboratif: '',
                media: '',
                materi_pembelajaran: '',
                referensi: ''
            });
        },
        removeAktivitasPembelajaran(index) {
            if (this.formData.aktivitasPembelajaranList.length > 1) {
                this.formData.aktivitasPembelajaranList.splice(index, 1);
            }
        },
        loadRpsData() {
            // Load data RPS yang sudah ada untuk edit
            if (!this.rpsData) return;
            
            console.log('Loading RPS data:', this.rpsData);
            
            const rps = this.rpsData;
            
            // Set mata kuliah
            this.formData.kode_matakuliah = rps.kode_matakuliah || '';
            this.formData.nama_matakuliah = rps.nama_matakuliah || '';
            this.formData.kode = rps.kode_matakuliah || '';
            this.formData.sks_numeric = parseInt(rps.sks, 10) || 0;
            this.formData.sks = (rps.sks || 0) + ' SKS';
            this.formData.semester = parseInt(rps.semester, 10) || 0;
            this.formData.semester_display = 'Semester ' + (rps.semester || '-');
            
            // Set basic fields
            this.formData.bahan_kajian = rps.bahan_kajian || '';
            this.formData.tanggal_penyusunan = rps.tanggal_penyusunan || '';
            this.formData.tanggal_penyusunan_display = rps.tanggal_penyusunan ? 
                new Date(rps.tanggal_penyusunan).toLocaleDateString('id-ID', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                }) : '';
            
            // Load Otorisasi fields
            this.formData.dosen_pengembang = rps.dosen_pengembang || '';
            this.formData.koordinasi_bk = rps.koordinasi_bk || '';
            this.formData.kaprodi = rps.kaprodi || '';
            this.formData.deskripsi_mk = rps.deskripsi_mk || '';
            
            // Load CPL-PRODI
            if (rps.cpl_prodi && Array.isArray(rps.cpl_prodi)) {
                this.formData.selectedCpl = rps.cpl_prodi;
            }
            
            // Load Indikator
            if (rps.indikator && Array.isArray(rps.indikator)) {
                this.formData.selectedIk = rps.indikator;
            }
            
            // Load CPMK
            if (rps.cpmk && Array.isArray(rps.cpmk) && rps.cpmk.length > 0) {
                this.formData.cpmkList = rps.cpmk.map(c => ({
                    deskripsi: c.deskripsi || ''
                }));
                // Rebuild korelasi array
                this.formData.korelasi = rps.cpmk.map(() => ({ cpl: [], ik: [] }));
            }
            
            // Load Korelasi
            if (rps.korelasi && Array.isArray(rps.korelasi)) {
                this.formData.korelasi = rps.korelasi;
            }
            
            // Load Asesmen
            if (rps.asesmen && Array.isArray(rps.asesmen) && rps.asesmen.length > 0) {
                this.formData.asesmenList = rps.asesmen.map(a => ({
                    jenis: a.jenis || '',
                    bobot: a.bobot || '',
                    keterangan: a.keterangan || ''
                }));
            }
            
            // Load Materi Pembelajaran
            if (rps.materi_pembelajaran && Array.isArray(rps.materi_pembelajaran) && rps.materi_pembelajaran.length > 0) {
                this.formData.materiList = rps.materi_pembelajaran.map(m => ({
                    isi: m.isi || m
                }));
            }
            
            // Load Pustaka Utama
            if (rps.pustaka_utama && Array.isArray(rps.pustaka_utama) && rps.pustaka_utama.length > 0) {
                this.formData.pustakaUtamaList = rps.pustaka_utama.map(p => ({
                    isi: p.isi || p
                }));
            }
            
            // Load Pustaka Pendukung
            if (rps.pustaka_pendukung && Array.isArray(rps.pustaka_pendukung) && rps.pustaka_pendukung.length > 0) {
                this.formData.pustakaPendukungList = rps.pustaka_pendukung.map(p => ({
                    isi: p.isi || p
                }));
            }
            
            // Load Perangkat Lunak
            if (rps.perangkat_lunak && Array.isArray(rps.perangkat_lunak) && rps.perangkat_lunak.length > 0) {
                this.formData.perangkatLunakList = rps.perangkat_lunak.map(p => ({
                    isi: p.isi || p
                }));
            }
            
            // Load Perangkat Keras
            if (rps.perangkat_keras && Array.isArray(rps.perangkat_keras) && rps.perangkat_keras.length > 0) {
                this.formData.perangkatKerasList = rps.perangkat_keras.map(p => ({
                    isi: p.isi || p
                }));
            }
            
            // Load Dosen Pengampu
            if (rps.dosen_pengampu && Array.isArray(rps.dosen_pengampu) && rps.dosen_pengampu.length > 0) {
                this.formData.dosenPengampuList = rps.dosen_pengampu.map(d => ({
                    nama: d.nama || d
                }));
            }
            
            // Load MK Prasyarat
            if (rps.mk_prasyarat && Array.isArray(rps.mk_prasyarat) && rps.mk_prasyarat.length > 0) {
                this.formData.mkPrasyaratList = rps.mk_prasyarat.map(m => ({
                    nama: m.nama || m
                }));
            }
            
            // Load Aktivitas Pembelajaran dari relasi (cek kedua nama: camelCase dan snake_case)
            const aktivitasList = rps.aktivitas_pembelajaran || rps.aktivitasPembelajaran || [];
            if (aktivitasList && Array.isArray(aktivitasList) && aktivitasList.length > 0) {
                this.formData.aktivitasPembelajaranList = aktivitasList.map(a => ({
                    minggu_ke: a.minggu_ke || '',
                    cpmk_kode: a.cpmk_kode || '',
                    indikator_penilaian: a.indikator_penilaian || '',
                    bentuk_penilaian_jenis: a.bentuk_penilaian_jenis || '',
                    bentuk_penilaian_bobot: a.bentuk_penilaian_bobot || '',
                    aktivitas_sinkron_luring: a.aktivitas_sinkron_luring || '',
                    aktivitas_sinkron_daring: a.aktivitas_sinkron_daring || '',
                    aktivitas_asinkron_mandiri: a.aktivitas_asinkron_mandiri || '',
                    aktivitas_asinkron_kolaboratif: a.aktivitas_asinkron_kolaboratif || '',
                    media: a.media || '',
                    materi_pembelajaran: a.materi_pembelajaran || '',
                    referensi: a.referensi || ''
                }));
            }
            
            console.log('RPS data loaded successfully');
            console.log('Aktivitas Pembelajaran:', this.formData.aktivitasPembelajaranList);
        },
        init() {
            // Pre-select mata kuliah dari URL parameter jika ada
            const urlParams = new URLSearchParams(window.location.search);
            const kodeParam = urlParams.get('kode');
            const semesterParam = urlParams.get('semester');
            
            setTimeout(() => {
                // Jika ada RPS data, load itu
                if (this.rpsData) {
                    this.loadRpsData();
                } else if (kodeParam) {
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

            // Validasi Otorisasi
            if (!this.formData.dosen_pengembang || this.formData.dosen_pengembang.trim() === '') {
                alert('Nama Dosen Pengembang RPS wajib diisi');
                return false;
            }

            if (!this.formData.koordinasi_bk || this.formData.koordinasi_bk.trim() === '') {
                alert('Nama Koordinasi BK wajib diisi');
                return false;
            }

            if (!this.formData.kaprodi || this.formData.kaprodi.trim() === '') {
                alert('Nama Kaprodi wajib diisi');
                return false;
            }

            // Validasi CPL-PRODI
            if (this.formData.selectedCpl.length === 0) {
                alert('Pilih minimal satu CPL-PRODI');
                return false;
            }

            // Validasi Indikator
            if (this.formData.selectedIk.length === 0) {
                alert('Pilih minimal satu Indikator (IK)');
                return false;
            }

            // Validasi CPMK
            let cpmkValid = true;
            this.formData.cpmkList.forEach((cpmk, index) => {
                if (!cpmk.deskripsi || cpmk.deskripsi.trim() === '') {
                    alert(`CPMK-${index + 1}: Deskripsi harus diisi`);
                    cpmkValid = false;
                }
            });
            if (!cpmkValid) return false;

            // Validasi Asesmen
            let asesmenValid = true;
            this.formData.asesmenList.forEach((asesmen, index) => {
                if (!asesmen.jenis || !asesmen.bobot) {
                    alert(`Asesmen ${index + 1}: Jenis dan Bobot harus diisi`);
                    asesmenValid = false;
                }
            });
            if (!asesmenValid) return false;

            // Validasi total bobot
            if (this.totalBobot !== 100) {
                alert(`Total bobot asesmen harus 100%. Saat ini: ${this.totalBobot}%`);
                return false;
            }

            // Validasi Deskripsi MK
            if (!this.formData.deskripsi_mk || this.formData.deskripsi_mk.trim() === '') {
                alert('Deskripsi Mata Kuliah harus diisi');
                return false;
            }

            // Validasi Materi Pembelajaran
            let materiValid = true;
            this.formData.materiList.forEach((materi, index) => {
                if (!materi.isi || materi.isi.trim() === '') {
                    alert(`Materi Pembelajaran ${index + 1} harus diisi`);
                    materiValid = false;
                }
            });
            if (!materiValid) return false;

            // Validasi Pustaka Utama
            let pustakaUtamaValid = true;
            this.formData.pustakaUtamaList.forEach((pustaka, index) => {
                if (!pustaka.isi || pustaka.isi.trim() === '') {
                    alert(`Pustaka Utama ${index + 1} harus diisi`);
                    pustakaUtamaValid = false;
                }
            });
            if (!pustakaUtamaValid) return false;

            // Form akan submit secara normal
            return true;
        }
    };
}

// Error handler
window.addEventListener('error', function(e) {
    console.error('Global error:', e.error);
});

// Check if Alpine is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Alpine === 'undefined') {
        console.error('Alpine.js is not loaded!');
        alert('Error: Alpine.js tidak ter-load. Silakan refresh halaman.');
    } else {
        console.log('Alpine.js loaded successfully');
    }
});
</script>
@endsection

