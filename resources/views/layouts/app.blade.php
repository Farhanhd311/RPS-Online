<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $title ?? 'Dashboard' }}</title>
	@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50">
    <div class="flex min-h-screen">
		@include('navigation.sidebar')
		<main class="flex-1 px-6 py-6">
			<!-- Top bar -->
			<div class="flex items-center justify-end">
				<div class="h-10 w-10 rounded-full bg-emerald-600/10 grid place-items-center text-emerald-700">
					<span class="i-heroicons-user"></span>
				</div>
			</div>
            @yield('content')
		</main>
	</div>
</body>
</html>
