@extends('layouts.app')

@section('content')
<div class="space-y-6 mt-4">
	<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
		<div class="flex items-center gap-3">
			<a href="{{ route('fakultas.programs', ['code'=>$code]) }}" class="inline-flex items-center">
				<img src="{{ asset('images/back-black.png') }}" alt="Back" class="h-7 w-7 object-contain" />
			</a>
			<h2 class="text-lg font-bold text-slate-900">{{ $program['name'] }}</h2>
		</div>
		<div class="grid grid-cols-12 gap-4 mt-4">
			<div class="col-span-12 sm:col-span-6">
				<div class="rounded-lg border border-slate-200 p-4">
					<p class="text-xs text-slate-500">Jumlah Dosen</p>
					<p class="mt-1 text-2xl font-semibold text-slate-800">{{ $program['stats']['dosen'] }}</p>
				</div>
			</div>
			<div class="col-span-12 sm:col-span-6">
				<div class="rounded-lg border border-slate-200 p-4">
					<p class="text-xs text-slate-500">Jumlah Mahasiswa</p>
					<p class="mt-1 text-2xl font-semibold text-slate-800">{{ $program['stats']['mahasiswa'] }}</p>
				</div>
			</div>
		</div>
	</div>

	@php($userRole = auth()->user()->role ?? 'dosen')
	<div class="grid grid-cols-12 gap-6">
		<div class="col-span-12 lg:col-span-4">
			<a href="{{ route('fakultas.rps', ['code'=>$code]) }}?role={{ $userRole }}" class="block rounded-xl border border-slate-200 p-4 shadow-sm hover:border-emerald-400 bg-white">
				<p class="font-medium">Rencana Pembelajaran Semester (RPS)</p>
			</a>
		</div>
		<div class="col-span-12 lg:col-span-4">
			<div class="rounded-xl border border-slate-200 p-4 shadow-sm bg-white">
				<p class="font-medium">Daftar Dosen</p>
			</div>
		</div>
		<div class="col-span-12 lg:col-span-4">
			<a href="{{ route('fakultas.struktur', ['code'=>$code]) }}" class="block rounded-xl border border-slate-200 p-4 shadow-sm hover:border-emerald-400 bg-white">
				<p class="font-medium">Struktur Organisasi</p>
			</a>
		</div>
	</div>

	<div class="grid grid-cols-12 gap-6">
		<div class="col-span-12 lg:col-span-6">
			<div class="rounded-xl border border-slate-200 p-4 shadow-sm bg-white">
				<p class="font-semibold">Kalender Fakultas</p>
				<ul class="mt-3 space-y-2">
					@foreach(($program['kalender'] ?? []) as $k)
						<li class="rounded-lg border border-slate-200 px-3 py-2 text-sm"><span class="font-medium">{{ $k['event'] }}</span><span class="text-slate-500"> — </span><span>{{ $k['tanggal'] }}</span></li>
					@endforeach
				</ul>
			</div>
		</div>
		<div class="col-span-12 lg:col-span-6">
			<div class="rounded-xl border border-slate-200 p-4 shadow-sm bg-white">
				<p class="font-semibold">Berita Fakultas</p>
				<ul class="mt-3 space-y-2">
					@foreach(($program['berita'] ?? []) as $b)
						<li class="rounded-lg border border-slate-200 px-3 py-2 text-sm">{{ $b['judul'] }}</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>

	<!-- Sections intentionally left with titles only as requested -->
</div>
@endsection

