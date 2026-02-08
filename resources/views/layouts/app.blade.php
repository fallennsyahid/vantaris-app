@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - Vantaris App' : config('app.name', 'Vantaris App') }}</title>

    <!-- Fonts -->
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,401,500,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="antialiased min-h-screen bg-linear-to-br from-section via-white to-accent">

    @include('layouts.sidebar')

    <div class="ml-74 mt-4 transition-all duration-700 ease-in-out" id="main-content">
        @include('layouts.navbar')

        <main class="p-6 pt-24">
            <div class="space-y-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

<script src="{{ asset('asset-admin/js/dashboard.js') }}"></script>

</html>
