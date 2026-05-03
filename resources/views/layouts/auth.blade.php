<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', $settings->site_name ?? config('app.name'))</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('assets/images/' . ($settings->favicon ?? 'default_favicon.png')) }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    @yield('content')
    @stack('scripts')
</body>
</html>
