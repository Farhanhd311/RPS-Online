@extends('layouts.app')

@section('content')
<div
	class="grid grid-cols-12 gap-6"
	x-data="facultyPage({{ json_encode($faculties) }})"
>
	<!-- Left: List -->
	<div class="col-span-12 xl:col-span-4">
		<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
			<!-- Searchbar -->
			<div class="relative" @keydown.window.prevent.slash="$refs.search?.focus()" role="search">
				<img src="{{ asset('images/search.png') }}" alt="Search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 opacity-70">
				<input
					x-ref="search"
					x-model.debounce.250ms="query"
					type="text"
					placeholder="Cari Fakultas"
					class="w-full rounded-lg border border-slate-200 pl-9 pr-9 py-2 text-sm placeholder-slate-400 focus:ring-emerald-500 focus:border-emerald-500"
				/>
				<button
					x-show="query.length"
					x-transition.opacity
					type="button"
					class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md px-2 py-1 text-[11px] font-medium text-slate-600 hover:bg-slate-100"
					@click="query=''; $refs.search.focus()"
				>
					Clear
				</button>
			</div>
			<!-- Result meta -->
			<div class="mt-2 text-xs text-slate-500" x-text="filtered.length + ' hasil'"></div>
			<h3 class="mt-5 text-slate-800 font-bold">FAKULTAS</h3>
			<div class="mt-3 space-y-3 max-h-[540px] overflow-auto pr-1">
				<template x-if="!filtered.length">
					<div class="text-sm text-slate-500 p-4 border border-dashed border-slate-200 rounded-lg">Tidak ada fakultas yang cocok.</div>
				</template>
				<template x-for="item in filtered" :key="item.code">
					<button @click="select(item)" class="w-full text-left flex items-center gap-4 rounded-xl border border-slate-200 px-4 py-4 hover:bg-emerald-50" :class="selected.code===item.code ? 'bg-emerald-50 border-emerald-200' : ''">
						<div class="h-12 w-12 shrink-0 grid place-items-center rounded-lg bg-slate-100 text-slate-800 font-bold" x-text="item.code"></div>
						<div class="min-w-0">
							<p class="text-slate-800 font-semibold truncate" x-text="item.name"></p>
							<p class="text-xs text-slate-500" x-text="item.departments + ' Departemen'"></p>
						</div>
					</button>
				</template>
			</div>
		</div>
	</div>

	<!-- Right: Details -->
	<div class="col-span-12 xl:col-span-8 space-y-6">
		<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
			<h2 class="text-xl font-bold text-slate-900">FAKULTAS <span x-text="formatFaculty(selected.name)"></span></h2>
			<div class="grid grid-cols-12 gap-4 mt-4">
				<div class="col-span-12 sm:col-span-4">
					<div class="rounded-lg border border-slate-200 p-4">
						<p class="text-xs text-slate-500">Jumlah Departemen</p>
						<p class="mt-1 text-2xl font-semibold text-slate-800" x-text="selected.stats.departments"></p>
					</div>
				</div>
				<div class="col-span-12 sm:col-span-4">
					<div class="rounded-lg border border-slate-200 p-4">
						<p class="text-xs text-slate-500">Jumlah Dosen</p>
						<p class="mt-1 text-2xl font-semibold text-slate-800" x-text="selected.stats.dosen"></p>
					</div>
				</div>
				<div class="col-span-12 sm:col-span-4">
					<div class="rounded-lg border border-slate-200 p-4">
						<p class="text-xs text-slate-500">Jumlah Mahasiswa</p>
						<p class="mt-1 text-2xl font-semibold text-slate-800" x-text="selected.stats.mahasiswa"></p>
					</div>
				</div>
			</div>
		</div>

		<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
			<div class="grid grid-cols-12 gap-6">
				<template x-for="card in selected.cards" :key="card.title">
					<div class="col-span-12 sm:col-span-6">
						<div class="h-full rounded-xl border border-slate-200 p-5 hover:border-emerald-300 transition cursor-pointer"
							@click="if(card.title==='Program Studi' || card.title==='Departemen'){ window.location.href = '/fakultas/' + selected.code + '/program-studi'; }"
						>
							<p class="text-lg font-semibold text-slate-800" x-text="card.title"></p>
							<p class="mt-4 text-xs text-slate-500" x-text="card.desc"></p>
						</div>
					</div>
				</template>
			</div>
		</div>
	</div>
</div>

<script>
function facultyPage(data) {
	return {
		all: data,
		query: '',
		selected: data[0],
		get filtered() {
			const q = this.query.toLowerCase().trim();
			if (!q) return this.all;
			return this.all.filter(f => f.name.toLowerCase().includes(q) || f.code.toLowerCase().includes(q));
		},
		select(item) { this.selected = item; },
		formatFaculty(name) { return name.replace(/^Fakultas\s+/i, '').toUpperCase(); },
	};
}
</script>
@endsection

