@extends('layouts.app')

@section('content')
<div x-data='rpsPage({{ json_encode($semesters) }})' class="space-y-6 mt-4">
    @php($userRole = auth()->user()->role ?? 'dosen')
    <div class="flex items-center gap-3">
        <a href="{{ route('fakultas.program.detail', ['code'=>$code, 'slug'=>'program-studi-s1-sistem-informasi']) }}?role={{ $userRole }}" class="inline-flex items-center">
            <img src="{{ asset('images/back.png') }}" alt="Back" class="h-7 w-7 object-contain" />
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-700">S1 Sistem Informasi</h1>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <span class="i-heroicons-check-circle text-emerald-600 text-xl mt-0.5"></span>
                <div class="flex-1">
                    <h4 class="font-semibold text-emerald-800">{{ session('success') }}</h4>
                </div>
            </div>
        </div>
    @endif
	<div class="bg-white rounded-xl p-6 shadow-sm ring-1 ring-slate-100">
		<div class="flex items-center justify-between mb-4">
			<p class="font-semibold text-slate-800">Pilih Semester</p>
			<button @click="showAddMataKuliah = true" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
				<span class="i-heroicons-plus text-lg"></span>
				<span>Tambah Mata Kuliah</span>
			</button>
		</div>
		<div class="mt-3 flex items-center gap-3">
			<div class="relative w-full">
				<select x-model.number="selected" class="w-full appearance-none rounded-xl border border-emerald-300 pl-4 pr-10 py-2.5 text-emerald-800 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white shadow-sm">
					<template x-for="s in all" :key="s.value">
						<option :value="s.value" x-text="s.label"></option>
					</template>
				</select>
				<span class="pointer-events-none i-heroicons-chevron-down-20-solid absolute right-3 top-1/2 -translate-y-1/2 text-emerald-600"></span>
			</div>
			<button class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold shadow hover:bg-emerald-700" @click="apply()">PILIH</button>
		</div>

		<div class="mt-8 space-y-3">
			<template x-for="c in courses" :key="c.kode || c.name">
				<div class="border border-slate-200 rounded-xl px-6 py-4 hover:border-emerald-400 hover:shadow-md transition-all bg-gradient-to-r from-white to-slate-50">
					<div class="flex items-center justify-between">
						<div class="flex-1">
							<div class="flex items-center gap-3 mb-1.5">
								<span x-show="c.kode" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full" x-text="c.kode"></span>
								<span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-full">
									<span x-text="c.sks || 0"></span> SKS
								</span>
								<template x-if="c.has_rps && c.rps_status">
									<span class="text-xs font-medium px-2.5 py-1 rounded-full" 
										  :class="{
											  'bg-yellow-100 text-yellow-800': c.rps_status === 'draft',
											  'bg-blue-100 text-blue-800': c.rps_status === 'submitted',
											  'bg-green-100 text-green-800': c.rps_status === 'approved',
											  'bg-red-100 text-red-800': c.rps_status === 'rejected'
										  }"
										  x-text="{
											  'draft': 'Draft',
											  'submitted': 'Menunggu Review',
											  'approved': 'Disetujui',
											  'rejected': 'Ditolak'
										  }[c.rps_status] || c.rps_status"></span>
								</template>
							</div>
							<p class="text-slate-800 font-medium text-base" x-text="c.name"></p>
						</div>
						<div class="flex items-center gap-4 ml-6">
							<!-- Lihat - only show if RPS exists -->
							<template x-if="c.has_rps">
								<a href="#" @click.prevent="view(c)" class="inline-flex items-center gap-2 text-sm text-emerald-700 hover:text-emerald-800 font-medium transition-colors">
									<span class="i-heroicons-eye text-lg"></span>
									<span>Lihat</span>
								</a>
							</template>
							
							<!-- Input - always show -->
							<a :href="`{{ route('dosen.input_rps', ['code'=>$code]) }}?kode=${c.kode || ''}&semester=${c.semester || ''}`" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
								<span class="i-heroicons-pencil text-lg"></span>
								<span x-text="c.has_rps ? 'Edit' : 'Input'"></span>
							</a>
							
							<!-- Unduh - only show if RPS exists -->
							<template x-if="c.has_rps">
								<a href="#" @click.prevent="download(c)" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-700 font-medium transition-colors">
									<span class="i-heroicons-arrow-down-tray text-lg"></span>
									<span>Unduh</span>
								</a>
							</template>
						</div>
					</div>
				</div>
			</template>
			<div x-show="!courses.length" class="text-center py-12 text-slate-500 bg-slate-50 rounded-xl border border-dashed border-slate-300">
				<span class="i-heroicons-academic-cap text-4xl text-slate-300 block mb-2"></span>
				<p class="text-sm font-medium">Tidak ada mata kuliah untuk semester ini.</p>
			</div>
		</div>
	</div>

	<!-- Modal preview sederhana -->
	<div x-show="preview" x-transition.opacity class="fixed inset-0 bg-black/40 grid place-items-center z-40 p-4">
		<div class="bg-white rounded-xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.outside="preview=null">
			<div class="flex items-start justify-between mb-4">
				<div class="flex-1">
					<div class="flex items-center gap-3 mb-2">
						<span x-show="preview?.kode" class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full" x-text="preview?.kode"></span>
						<span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
							<span x-text="preview?.sks || 0"></span> SKS
						</span>
						<span class="text-xs text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full">
							Semester <span x-text="preview?.semester || '-'"></span>
						</span>
					</div>
					<p class="text-lg font-semibold text-slate-800" x-text="preview?.name"></p>
				</div>
				<button @click="preview=null" class="ml-4 text-slate-400 hover:text-slate-600 transition-colors">
					<span class="i-heroicons-x-mark text-xl"></span>
				</button>
			</div>
			<div class="border-t border-slate-200 pt-4">
				<p class="text-sm text-slate-600">Pratinjau RPS (mock). Tempatkan embed PDF atau konten di sini.</p>
			</div>
			<div class="mt-6 flex justify-end gap-3">
				<button class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors" @click="preview=null">Tutup</button>
				<button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition-colors">Buka Detail</button>
			</div>
		</div>
	</div>

	<!-- Modal Tambah Mata Kuliah -->
	<div x-show="showAddMataKuliah" x-transition.opacity class="fixed inset-0 bg-black/40 grid place-items-center z-50 p-4">
		<div class="bg-white rounded-xl p-6 w-full max-w-md" @click.outside="showAddMataKuliah = false">
			<div class="flex items-center justify-between mb-4">
				<h3 class="text-lg font-semibold text-slate-800">Tambah Mata Kuliah Baru</h3>
				<button @click="showAddMataKuliah = false" class="text-slate-400 hover:text-slate-600 transition-colors">
					<span class="i-heroicons-x-mark text-xl"></span>
				</button>
			</div>
			
			<form @submit.prevent="submitMataKuliah()">
				<div class="space-y-4">
					<div>
						<label for="kode_matakuliah" class="block text-sm font-medium text-slate-700 mb-1">Kode Mata Kuliah</label>
						<input type="text" id="kode_matakuliah" x-model="newMataKuliah.kode_matakuliah" 
							   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
							   placeholder="Contoh: SI001" required>
					</div>
					
					<div>
						<label for="nama_matakuliah" class="block text-sm font-medium text-slate-700 mb-1">Nama Mata Kuliah</label>
						<input type="text" id="nama_matakuliah" x-model="newMataKuliah.nama_matakuliah" 
							   class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
							   placeholder="Contoh: Pemrograman Web" required>
					</div>
					
					<div class="grid grid-cols-2 gap-4">
						<div>
							<label for="sks" class="block text-sm font-medium text-slate-700 mb-1">SKS</label>
							<select id="sks" x-model="newMataKuliah.sks" 
									class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
								<option value="">Pilih SKS</option>
								<option value="1">1 SKS</option>
								<option value="2">2 SKS</option>
								<option value="3">3 SKS</option>
								<option value="4">4 SKS</option>
								<option value="5">5 SKS</option>
								<option value="6">6 SKS</option>
							</select>
						</div>
						
						<div>
							<label for="semester" class="block text-sm font-medium text-slate-700 mb-1">Semester</label>
							<select id="semester" x-model="newMataKuliah.semester" 
									class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
								<option value="">Pilih Semester</option>
								<option value="1">Semester 1</option>
								<option value="2">Semester 2</option>
								<option value="3">Semester 3</option>
								<option value="4">Semester 4</option>
								<option value="5">Semester 5</option>
								<option value="6">Semester 6</option>
								<option value="7">Semester 7</option>
								<option value="8">Semester 8</option>
							</select>
						</div>
					</div>
				</div>
				
				<div class="flex gap-3 mt-6">
					<button type="submit" :disabled="isSubmitting" 
							class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
						<span x-show="!isSubmitting">Simpan</span>
						<span x-show="isSubmitting">Menyimpan...</span>
					</button>
					<button type="button" @click="showAddMataKuliah = false" 
							class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
						Batal
					</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
function rpsPage(semesters) {
	return {
		all: semesters,
		selected: semesters[0]?.value ?? 1,
		courses: semesters[0]?.courses ?? [],
		preview: null,
		showAddMataKuliah: false,
		isSubmitting: false,
		newMataKuliah: {
			kode_matakuliah: '',
			nama_matakuliah: '',
			sks: '',
			semester: ''
		},
		apply() {
			const s = this.all.find(s => s.value === this.selected);
			this.courses = s ? s.courses : [];
		},
		view(course) { 
			if (!course.has_rps || !course.rps_id) {
				alert('RPS belum dibuat untuk mata kuliah ini.');
				return;
			}
			// Open PDF in new tab
			const url = `{{ route('rps.view', ['code' => $code, 'rps_id' => '__RPS_ID__']) }}`.replace('__RPS_ID__', course.rps_id);
			window.open(url, '_blank');
		},
		download(course) { 
			if (!course.has_rps || !course.rps_id) {
				alert('RPS belum dibuat untuk mata kuliah ini.');
				return;
			}
			// Download PDF
			const url = `{{ route('rps.download', ['code' => $code, 'rps_id' => '__RPS_ID__']) }}`.replace('__RPS_ID__', course.rps_id);
			window.location.href = url;
		},
		async submitMataKuliah() {
			if (this.isSubmitting) return;
			
			this.isSubmitting = true;
			
			try {
				const response = await fetch(`{{ route('dosen.mata_kuliah.store', ['code' => $code]) }}`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
					},
					body: JSON.stringify(this.newMataKuliah)
				});
				
				const result = await response.json();
				
				if (result.success) {
					// Reset form
					this.newMataKuliah = {
						kode_matakuliah: '',
						nama_matakuliah: '',
						sks: '',
						semester: ''
					};
					this.showAddMataKuliah = false;
					
					// Show success message
					alert('Mata kuliah berhasil ditambahkan!');
					
					// Reload page to show new mata kuliah
					window.location.reload();
				} else {
					alert('Error: ' + result.message);
				}
			} catch (error) {
				console.error('Error:', error);
				alert('Terjadi kesalahan saat menambahkan mata kuliah');
			} finally {
				this.isSubmitting = false;
			}
		}
	};
}
</script>
@endsection

