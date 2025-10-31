@extends('layouts.app')

@section('content')
@php($email = auth()->user()->email)

<div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-[1fr_360px]">
	<!-- Hero welcome card with image background -->
	<div class="relative overflow-hidden rounded-2xl shadow-sm ring-1 ring-emerald-900/10">
		<div class="absolute inset-0">
			<div class="h-full w-full bg-center bg-cover" style="background-image:url('{{ asset('images/BG.png') }}');"></div>
			<div class="absolute inset-0 bg-emerald-900/60"></div>
		</div>
		<div class="relative p-8 text-white">
			<div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-[1fr_340px]">
				<div>
					<h1 class="text-3xl font-extrabold tracking-wide">Selamat Datang,</h1>
					<p class="mt-2 text-xl font-semibold">{{ $email }} !</p>
					<p class="mt-6 text-white/90">Let's learning something today!</p>
					<p class="text-white/90">Set your study plan and growth with community</p>
				</div>
				<div class="rounded-xl overflow-hidden">
					<img src="{{ asset('images/unand.jpg') }}" alt="kampus" class="h-40 w-full object-cover">
				</div>
			</div>
		</div>
	</div>

	<!-- Calendar card -->
	<div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100">
		<div class="flex items-center justify-between">
			<h3 class="text-lg font-bold text-slate-800">Kalender Akademik</h3>
			<div class="flex items-center gap-2">
				<button id="cal-prev" class="h-8 w-8 rounded-full bg-slate-100 grid place-items-center">‹</button>
				<div id="cal-month" class="text-sm font-semibold text-slate-700"></div>
				<button id="cal-next" class="h-8 w-8 rounded-full bg-slate-100 grid place-items-center">›</button>
			</div>
		</div>
		<div class="mt-3 grid grid-cols-7 text-center text-xs font-semibold text-slate-500">
			<span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
		</div>
		<div id="cal-grid" class="mt-2 grid grid-cols-7 gap-1 text-center text-sm"></div>
	</div>
</div>

<!-- footer -->
<div class="mt-10 rounded-2xl bg-white p-4 text-sm text-slate-500 shadow-sm ring-1 ring-slate-100">
	<div class="flex items-center justify-end gap-6">
		<a href="#" class="hover:text-slate-700">Contact</a>
		<a href="#" class="hover:text-slate-700">Licence</a>
		<a href="#" class="hover:text-slate-700">Support</a>
	</div>
</div>

<script>
(function(){
	const monthEl = document.getElementById('cal-month');
	const gridEl = document.getElementById('cal-grid');
	const prevBtn = document.getElementById('cal-prev');
	const nextBtn = document.getElementById('cal-next');
	let current = new Date();

	function render(){
		const year = current.getFullYear();
		const month = current.getMonth();
		const monthName = current.toLocaleString('default', {month:'long'});
		monthEl.textContent = `${monthName} ${year}`;
		gridEl.innerHTML = '';

		const first = new Date(year, month, 1);
		const startDay = first.getDay();
		const daysInMonth = new Date(year, month+1, 0).getDate();
		const today = new Date();

		for(let i=0;i<startDay;i++){
			const cell = document.createElement('div');
			cell.className = 'h-9';
			gridEl.appendChild(cell);
		}
		for(let d=1; d<=daysInMonth; d++){
			const cell = document.createElement('div');
			const isToday = d===today.getDate() && month===today.getMonth() && year===today.getFullYear();
			cell.className = 'h-9 grid place-items-center rounded-full ' + (isToday ? 'bg-emerald-600 text-white font-semibold' : 'hover:bg-slate-100');
			cell.textContent = d;
			gridEl.appendChild(cell);
		}
	}

	prevBtn.addEventListener('click', ()=>{ current.setMonth(current.getMonth()-1); render(); });
	nextBtn.addEventListener('click', ()=>{ current.setMonth(current.getMonth()+1); render(); });
	render();
})();
</script>
@endsection
