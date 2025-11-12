<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ $title ?? 'Dashboard' }}</title>
	@vite(['resources/css/app.css','resources/js/app.js'])
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-slate-50">
    <div class="flex min-h-screen">
		@include('navigation.sidebar')
        <div class="flex-1 flex flex-col">
            <!-- Top bar -->
            <div class="flex items-center justify-end bg-white px-4 py-3">
                @include('navigation.topbar')
            </div>
            <main class="flex-1 px-6 py-6">
                @yield('content')
            </main>
		</div>
	</div>
</body>
</html>
