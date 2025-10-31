@extends('layouts.app')

@section('content')
<div class="space-y-6 mt-4">
	<div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-slate-100">
		<div class="flex items-center gap-3">
			<a href="{{ route('fakultas.programs', ['code'=>$code]) }}" class="inline-flex items-center">
				<img src="{{ asset('images/back-black.png') }}" alt="Back" class="h-7 w-7 object-contain" />
			</a>
			<h2 class="text-lg font-bold text-slate-900">Struktur Organisasi</h2>
		</div>
	</div>

	<div class="bg-white rounded-xl p-4 shadow-sm ring-1 ring-slate-100">
		<img src="{{ asset('images/struktur.png') }}" alt="Struktur Organisasi" class="mx-auto w-full max-w-3xl h-auto rounded-lg object-contain" />
	</div>
</div>
@endsection




