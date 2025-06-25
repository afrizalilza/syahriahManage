<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins text-primary-dark antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-light-gray">
        <div class="flex flex-col items-center">
            <a href="/">
                <img src="/assets/images/logo.png" alt="Logo"
                    class="w-28 h-28 sm:w-36 sm:h-36 mx-auto mb-4 drop-shadow-lg rounded-full border-4 border-primary-light bg-white" />
            </a>
            <h1 class="text-2xl font-bold text-primary-dark tracking-tight mt-2 mb-1">Selamat Datang</h1>
            <p class="text-primary text-sm mb-2 text-center">Silakan login untuk mengakses aplikasi Syahriah Manage</p>
        </div>
        <div
            class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-primary-light">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
