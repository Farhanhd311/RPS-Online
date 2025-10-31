<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home</title>
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50">
	<div class="mx-auto max-w-6xl p-6">
		<div class="flex items-center justify-between">
			<h1 class="text-2xl font-bold text-emerald-700">Dashboard</h1>
			<form action="{{ route('logout') }}" method="post">
				@csrf
				<button class="rounded-md bg-emerald-600 px-4 py-2 text-white font-semibold hover:bg-emerald-700">Logout</button>
			</form>
		</div>

		<div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
			<p class="text-slate-700">Selamat datang, <span class="font-semibold">{{ auth()->user()->nama }}</span> (role: {{ auth()->user()->role }})</p>
		</div>
	</div>
</body>
</html>
