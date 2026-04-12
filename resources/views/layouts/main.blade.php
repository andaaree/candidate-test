<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <link rel="stylesheet" href="dist/css/style.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Material UI (MUI) via CDN (React-free alternative: Material Tailwind-like styles) -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- from cdn -->
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/collapse.js"></script>
    <!-- ripple dark from cdn -->
    <script async src="https://unpkg.com/@material-tailwind/html@latest/scripts/ripple.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('js')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="bg-gray-100">

    <div x-data="{ open: false }" class="min-h-screen flex flex-col">

        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            @yield('navbar')
        </header>

        <!-- 📦 Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

</body>

</html>
