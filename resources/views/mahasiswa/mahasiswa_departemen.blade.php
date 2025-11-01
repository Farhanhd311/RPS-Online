@extends('layouts.app')

@section('content')
@php($userRole = auth()->user()->role ?? 'mahasiswa')
<div class="space-y-6 mt-4" x-data="departemenPage({{ json_encode($faculty) }}, '{{ $userRole }}')">
	<!-- Header stats -->
	<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
		<div class="flex items-center gap-3">
			<a href="{{ route('fakultas.index') }}" class="inline-flex items-center">
				<img src="{{ asset('images/back-black.png') }}" alt="Back" class="h-7 w-7 object-contain" />
			</a>
			<h2 class="text-xl font-bold text-slate-900">FAKULTAS {{ strtoupper(preg_replace('/^Fakultas\s+/i', '', $faculty['name'])) }}</h2>
		</div>
		<div class="grid grid-cols-12 gap-4 mt-4 w-full">
			<div class="col-span-12 sm:col-span-4">
				<div class="rounded-lg border border-slate-200 p-4">
					<p class="text-xs text-slate-500">Jumlah Departemen</p>
					<p class="mt-1 text-2xl font-semibold text-slate-800">{{ $faculty['stats']['departments'] }}</p>
				</div>
			</div>
			<div class="col-span-12 sm:col-span-4">
				<div class="rounded-lg border border-slate-200 p-4">
					<p class="text-xs text-slate-500">Jumlah Dosen</p>
					<p class="mt-1 text-2xl font-semibold text-slate-800">{{ $faculty['stats']['dosen'] }}</p>
				</div>
			</div>
			<div class="col-span-12 sm:col-span-4">
				<div class="rounded-lg border border-slate-200 p-4">
					<p class="text-xs text-slate-500">Jumlah Mahasiswa</p>
					<p class="mt-1 text-2xl font-semibold text-slate-800">{{ $faculty['stats']['mahasiswa'] }}</p>
				</div>
			</div>
		</div>
	</div>

	<div class="grid grid-cols-12 gap-6">
		<!-- Left: Departemen list -->
		<div class="col-span-12 xl:col-span-6">
			<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
				<h3 class="text-lg font-semibold text-slate-900">Departemen</h3>
				<div class="mt-4 space-y-4">
					<template x-for="(d, idx) in departemens" :key="idx">
						<div x-data="{ isSI: d.name === 'Program Studi S1 Sistem Informasi' }">
							<div x-show="isSI" @click="handleSIClick(idx, d.name)" class="block rounded-xl border px-4 py-4 shadow-sm hover:shadow hover:border-emerald-300 cursor-pointer" :class="selectedIndex===idx ? 'border-emerald-300 shadow bg-emerald-50' : 'border-slate-200'">
								<p class="font-medium text-slate-800" x-text="d.name"></p>
								<p x-show="siClicked && selectedIndex===idx" class="mt-1 text-xs text-emerald-600">Klik lagi untuk melihat detail</p>
							</div>
							<div x-show="!isSI" class="block rounded-xl border px-4 py-4 border-slate-200 cursor-pointer" :class="selectedIndex===idx ? 'border-emerald-300 shadow bg-emerald-50' : 'hover:bg-slate-50'" @click="select(idx)">
								<p class="font-medium text-slate-800" x-text="d.name"></p>
							</div>
						</div>
					</template>
				</div>
				
			</div>
		</div>

		<!-- Right: Detail -->
		<div class="col-span-12 xl:col-span-6">
			<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
				<h3 class="text-center text-lg font-bold text-slate-900" x-text="departemens[selectedIndex].name"></h3>
				<div class="mt-4 prose max-w-none">
					<h4 class="text-center">VISI</h4>
					<p x-text="departemens[selectedIndex].visi"></p>
					<h4 class="text-center mt-6">MISI</h4>
					<ol class="list-decimal pl-6 space-y-1">
						<template x-for="m in departemens[selectedIndex].misi" :key="m">
							<li x-text="m"></li>
						</template>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function departemenPage(faculty, role) {
	return {
		departemens: faculty.programs,
		selectedIndex: 0,
		siClicked: false,
		userRole: role,
		select(i) { 
			this.selectedIndex = i; 
			// Reset siClicked jika pilih item lain
			if (this.departemens[i].name !== 'Program Studi S1 Sistem Informasi') {
				this.siClicked = false;
			}
		},
		handleSIClick(idx, name) {
			if (this.siClicked && this.selectedIndex === idx) {
				// Klik kedua: redirect ke detail berdasarkan role
				window.location.href = this.detailHref(name);
			} else {
				// Klik pertama: tampilkan konten
				this.siClicked = true;
				this.select(idx);
			}
		},
		detailHref(name) {
			const slug = name.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'');
			// Redirect berdasarkan role
			if (this.userRole === 'dosen') {
				return `/fakultas/FTI/program-studi/${slug}?role=dosen`;
			} else if (this.userRole === 'reviewer') {
				return `/fakultas/FTI/program-studi/${slug}?role=reviewer`;
			} else {
				return `/fakultas/FTI/program-studi/${slug}`;
			}
		},
	};
}
</script>
@endsection

