@extends('layouts.app')

@section('content')
<div x-data='rpsPage({{ json_encode($semesters) }})' class="space-y-6 mt-4">
    @php($userRole = auth()->user()->role ?? 'dosen')
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('fakultas.program.detail', ['code'=>$code, 'slug'=>'program-studi-s1-sistem-informasi']) }}?role={{ $userRole }}" class="inline-flex items-center">
                <img src="{{ asset('images/back.png') }}" alt="Back" class="h-7 w-7 object-contain" />
            </a>
            <h1 class="text-3xl font-extrabold text-emerald-700">S1 Sistem Informasi</h1>
        </div>
        <a href="{{ route('dosen.input_rps', ['code'=>$code]) }}" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white font-semibold shadow hover:bg-emerald-700 inline-flex items-center gap-2">
            <span class="i-heroicons-plus-circle"></span>
            Input RPS
        </a>
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

		<div class="mt-8 space-y-4">
			<template x-for="c in courses" :key="c.name">
				<div class="rounded-full border border-emerald-500 px-6 py-4 flex items-center justify-between hover:shadow-sm">
					<p class="text-slate-800" x-text="c.name"></p>
					<div class="flex items-center gap-6 text-emerald-700">
						<a href="#" @click.prevent="view(c)" class="inline-flex items-center gap-2"><span class="i-heroicons-eye"></span>Lihat</a>
						<a href="{{ route('dosen.input_rps', ['code'=>$code]) }}" class="inline-flex items-center gap-2"><span class="i-heroicons-pencil"></span>Input</a>
						<a href="#" @click.prevent="download(c)" class="inline-flex items-center gap-2"><span class="i-heroicons-arrow-down-tray"></span>Unduh</a>
					</div>
				</div>
			</template>
			<div x-show="!courses.length" class="text-sm text-slate-500">Tidak ada mata kuliah untuk semester ini.</div>
		</div>
	</div>

	<!-- Modal preview sederhana -->
	<div x-show="preview" x-transition.opacity class="fixed inset-0 bg-black/40 grid place-items-center z-40">
		<div class="bg-white rounded-xl p-5 w-full max-w-xl" @click.outside="preview=null">
			<p class="text-lg font-semibold" x-text="preview?.name"></p>
			<p class="mt-2 text-sm text-slate-600">Pratinjau RPS (mock). Tempatkan embed PDF atau konten di sini.</p>
			<div class="mt-5 text-right">
				<button class="px-3 py-1.5 rounded-lg border" @click="preview=null">Tutup</button>
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
		preview: null,
		apply() {
			const s = this.all.find(s => s.value === this.selected);
			this.courses = s ? s.courses : [];
		},
		view(course) { this.preview = course; },
		download(course) { alert('Unduh: ' + course.name); },
	};
}
</script>
@endsection

