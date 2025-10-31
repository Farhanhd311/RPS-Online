@extends('layouts.app')

@section('content')
<div class="space-y-6 mt-4" x-data="programPage({{ json_encode($faculty) }})">
	<!-- Header stats -->
	<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
		@php
			$__display = preg_replace('/^Fakultas\s+/i', '', $faculty['name']);
		@endphp
		<div class="flex items-center gap-3">
			<a href="{{ route('fakultas.index') }}" class="inline-flex items-center">
				<img src="{{ asset('images/back-black.png') }}" alt="Back" class="h-7 w-7 object-contain" />
			</a>
			<h2 class="text-xl font-bold text-slate-900">FAKULTAS {{ strtoupper($__display) }}</h2>
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
		<!-- Left: Program list -->
		<div class="col-span-12 xl:col-span-6">
			<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
				<h3 class="text-lg font-semibold text-slate-900">Departemen</h3>
				<div class="mt-4 space-y-4">
					<template x-for="(p, idx) in programs" :key="p.name">
						<a :href="detailHref(p.name)" @click.prevent="select(idx); window.location.href = detailHref(p.name);" class="block rounded-xl border px-4 py-4 shadow-sm hover:shadow {{ request()->routeIs('fakultas.programs') ? '' : '' }}" :class="selectedIndex===idx ? 'border-emerald-300 shadow' : 'border-slate-200'">
							<p class="font-medium text-slate-800" x-text="p.name"></p>
						</a>
					</template>
				</div>
				
			</div>
		</div>

		<!-- Right: Detail -->
		<div class="col-span-12 xl:col-span-6">
			<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
				<h3 class="text-center text-lg font-bold text-slate-900" x-text="programs[selectedIndex].name"></h3>
				<div class="mt-4 prose max-w-none">
					<h4 class="text-center">VISI</h4>
					<p x-text="programs[selectedIndex].visi"></p>
					<h4 class="text-center mt-6">MISI</h4>
					<ol class="list-decimal pl-6 space-y-1">
						<template x-for="m in programs[selectedIndex].misi" :key="m">
							<li x-text="m"></li>
						</template>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function programPage(faculty) {
	return {
		programs: faculty.programs,
		selectedIndex: 0,
		select(i) { this.selectedIndex = i; },
		detailHref(name) {
			const slug = name.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/(^-|-$)/g,'');
			return `/fakultas/FTI/program-studi/${slug}`;
		},
	};
}
</script>
@endsection


