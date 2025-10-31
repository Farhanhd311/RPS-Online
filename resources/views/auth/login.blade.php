<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign in — Layanan Akademik</title>
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="mx-auto max-w-[1400px] px-6 py-10 lg:py-12">
		<div class="grid grid-cols-1 items-start gap-12 lg:grid-cols-[1fr_780px]">
			<!-- Left: form -->
			<div class="max-w-2xl">
				<div class="flex items-center gap-4">
					<img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-14 w-14 object-contain" onerror="this.style.display='none'">
					<div>
						<p class="text-emerald-700 font-semibold text-[18px] leading-tight tracking-wide">MY UNAND</p>
						<p class="text-emerald-800 text-[22px] font-semibold -mt-1">Universitas Andalas</p>
					</div>
				</div>

				<h1 class="mt-10 text-4xl font-extrabold tracking-[0.02em] text-emerald-700">LAYANAN AKADEMIK</h1>

				<div class="mt-8 space-y-6">
					<h2 class="text-2xl font-bold">Sign in</h2>

					<form class="space-y-5" action="{{ route('login.perform') }}" method="post">
						@csrf
						<!-- Email -->
						<label class="block">
							<span class="block text-sm font-semibold">Email</span>
							<div class="mt-2 flex items-center rounded-xl border-2 border-emerald-600 bg-white px-4 py-3 focus-within:ring-2 focus-within:ring-emerald-500">
								<input type="email" name="email" placeholder="Type your username" class="w-full outline-none placeholder:text-slate-400 text-[15px]" />
							</div>
						</label>

						<!-- Password -->
						<label class="block">
							<span class="block text-sm font-semibold">Password</span>
							<div class="mt-2 flex items-center rounded-xl border-2 border-emerald-600 bg-white px-4 py-3 focus-within:ring-2 focus-within:ring-emerald-500">
								<input id="password-input" type="password" name="password" placeholder="Enter Current Password" class="w-full outline-none placeholder:text-slate-400 text-[15px]" />
								<button id="toggle-password" type="button" class="ml-2" aria-label="toggle password visibility">
									<img id="toggle-password-icon" src="{{ asset('images/hide.png') }}" alt="toggle" class="h-5 w-5 select-none" />
								</button>
							</div>
						</label>

						<div class="flex items-center justify-between text-sm">
							<label class="inline-flex items-center gap-2">
								<input type="checkbox" name="remember" class="size-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
								<span>Remember Me</span>
							</label>
							<a href="#" class="font-semibold text-emerald-700 hover:underline">Forgot password?</a>
						</div>

						<button type="submit" class="mt-2 w-full rounded-full bg-emerald-700 px-6 py-4 text-white text-[16px] font-semibold hover:bg-emerald-800">Sign in</button>

						<div class="my-6 flex items-center gap-3">
							<div class="h-px flex-1 bg-slate-200"></div>
							<span class="text-slate-500">or</span>
							<div class="h-px flex-1 bg-slate-200"></div>
						</div>

						<button type="button" class="w-full rounded-full border-2 border-emerald-600 bg-white px-6 py-4 text-emerald-700 text-[16px] font-semibold hover:bg-emerald-50">Login with SSO</button>
					</form>
				</div>
			</div>

			<!-- Right: image -->
			<div class="order-first lg:order-none w-full">
				<div class="rounded-2xl">
					<img src="{{ asset('images/unand.jpg') }}" alt="Kampus" class="h-[720px] w-full rounded-2xl object-cover" />
				</div>
			</div>
		</div>
	</div>
</body>
<script>
    (function() {
        const input = document.getElementById('password-input');
        const btn = document.getElementById('toggle-password');
        const icon = document.getElementById('toggle-password-icon');
        if (!input || !btn || !icon) return;
        const hideSrc = "{{ asset('images/hide.png') }}";
        const unhideSrc = "{{ asset('images/unhide.png') }}";
        btn.addEventListener('click', function() {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.src = isHidden ? unhideSrc : hideSrc;
        });
    })();
</script>
</html>

