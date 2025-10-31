<div class="relative">
	<button id="profile-button" type="button" class="flex items-center gap-3 rounded-full bg-emerald-600/10 p-1.5 pr-3 text-emerald-700 hover:bg-emerald-600/20">
		<img src="{{ asset('images/profile.png') }}" alt="profile" class="h-10 w-10 rounded-full object-cover">
		<span class="hidden sm:block text-sm font-semibold text-slate-700 max-w-[200px] truncate">{{ auth()->user()->email }}</span>
	</button>
</div>

<!-- Floating overlay + panel (does not push layout) -->
<div id="profile-modal" class="fixed inset-0 z-50 hidden">
	<div class="absolute inset-0 bg-black/20"></div>
	<!-- Floating panel at top-right -->
	<div class="fixed right-6 top-16 w-[420px] max-w-[92vw] rounded-2xl bg-white shadow-2xl ring-1 ring-slate-200">
		<div class="p-6">
			<div class="flex items-start gap-3">
				<img src="{{ asset('images/profile.png') }}" alt="profile" class="h-12 w-12 rounded-full object-cover">
				<div class="min-w-0">
					<p class="text-[22px] font-extrabold leading-snug text-slate-900 break-words">{{ auth()->user()->email }}</p>
					<p class="text-sm text-slate-500 truncate">{{ auth()->user()->email }}</p>
				</div>
			</div>
			<hr class="my-5 border-slate-200">
			<a href="#" class="flex items-center gap-3 rounded-lg px-2 py-2 text-slate-700 hover:bg-slate-50">
				<span class="i-heroicons-cog-6-tooth text-blue-600"></span>
				<span class="font-semibold">Profil</span>
			</a>
			<hr class="my-5 border-slate-200">
			<form action="{{ route('logout') }}" method="post">
				@csrf
				<button class="flex w-full items-center gap-3 rounded-lg px-2 py-2 text-slate-700 hover:bg-slate-50" type="submit">
					<span class="i-heroicons-arrow-left-on-rectangle text-rose-600"></span>
					<span class="font-semibold">Log Out</span>
				</button>
			</form>
		</div>
	</div>
</div>

<script>
(function(){
	const btn = document.getElementById('profile-button');
	const modal = document.getElementById('profile-modal');
	if (!btn || !modal) return;
	function open(){ modal.classList.remove('hidden'); }
	function close(){ modal.classList.add('hidden'); }
	btn.addEventListener('click', (e)=>{ e.stopPropagation(); open(); });
	modal.addEventListener('click', (e)=>{
		// close when clicking overlay or outside card
		const card = modal.querySelector('div.relative.mx-auto');
		if (!card || !card.contains(e.target)) close();
	});
	document.addEventListener('keydown', (e)=>{ if (e.key === 'Escape') close(); });
})();
</script>

