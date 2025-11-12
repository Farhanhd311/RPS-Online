@extends('layouts.app')

@section('content')
<div x-data='rpsPage({{ json_encode($semesters) }})' class="space-y-6 mt-4">
    @php($userRole = auth()->user()->role ?? 'mahasiswa')
    <div class="flex items-center gap-3">
        <a href="{{ route('fakultas.program.detail', ['code'=>$code, 'slug'=>'program-studi-s1-sistem-informasi']) }}?role={{ $userRole }}" class="inline-flex items-center">
            <img src="{{ asset('images/back.png') }}" alt="Back" class="h-7 w-7 object-contain" />
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-700">S1 Sistem Informasi</h1>
    </div>
	<div class="bg-white rounded-xl p-6 shadow-sm ring-1 ring-slate-100">
		<p class="font-semibold text-slate-800">Pilih Semester</p>
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
							</div>
							<p class="text-slate-800 font-medium text-base" x-text="c.name"></p>
						</div>
						<div class="flex items-center gap-4 ml-6">
							<template x-if="c.has_rps && c.rps_id">
								<div class="flex items-center gap-4">
									<a :href="`/mahasiswa/{{ $code }}/rps/${c.rps_id}/view`" target="_blank" class="inline-flex items-center gap-2 text-sm text-emerald-700 hover:text-emerald-800 font-medium transition-colors">
										<span class="i-heroicons-eye text-lg"></span>
										<span>Lihat PDF</span>
									</a>
									<a :href="`/mahasiswa/{{ $code }}/rps/${c.rps_id}/download`" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-700 font-medium transition-colors">
										<span class="i-heroicons-arrow-down-tray text-lg"></span>
										<span>Unduh PDF</span>
									</a>
								</div>
							</template>
							<template x-if="!c.has_rps">
								<div class="text-sm text-slate-400 italic">
									RPS belum tersedia
								</div>
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

</div>

<script>
function rpsPage(semesters) {
	return {
		all: semesters,
		selected: semesters[0]?.value ?? 1,
		courses: semesters[0]?.courses ?? [],
		apply() {
			const s = this.all.find(s => s.value === this.selected);
			this.courses = s ? s.courses : [];
		}
	};
}
</script>
@endsection

