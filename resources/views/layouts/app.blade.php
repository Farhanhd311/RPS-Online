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
                @include('navigation.topbar')
            </div>
            @yield('content')
		</main>
	</div>
</body>
</html>
