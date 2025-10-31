<aside class="w-64 shrink-0 min-h-screen bg-white shadow-sm ring-1 ring-slate-100">
	<div class="p-6">
		<div class="flex items-center gap-3">
			<img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 object-contain">
			<div>
				<p class="text-emerald-700 font-semibold leading-tight">MY UNAND</p>
				<p class="-mt-0.5 text-slate-700 text-sm">Universitas Andalas</p>
			</div>
		</div>
		<nav class="mt-6 space-y-2">
			<a href="{{ route('home') }}" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700' : '' }}">
				<span class="i-heroicons-home"></span>
				Home
			</a>
			<a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
				<span class="i-heroicons-user"></span>
				Profil Biodata
			</a>
			<a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
				<span class="i-heroicons-building-office"></span>
				Fakultas
			</a>
			<a href="#" class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
				<span class="i-heroicons-information-circle"></span>
				About
			</a>
		</nav>
	</div>
</aside>
