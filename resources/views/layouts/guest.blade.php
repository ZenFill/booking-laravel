<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 bg-gradient-to-br from-blue-50 to-indigo-100 dark:bg-gray-900">
        <div class="mb-6 flex flex-col items-center">
            <a href="/" class="flex flex-col items-center gap-2 group">
                <div
                    class="bg-white p-3 rounded-2xl shadow-lg border border-blue-50 group-hover:scale-105 transition duration-300">
                    <x-application-logo class="w-12 h-12 text-blue-600" />
                </div>
                <span class="font-extrabold text-2xl text-blue-900 tracking-tight">Studio<span
                        class="text-blue-600">Book</span></span>
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-2 px-8 py-8 bg-white shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100">
            {{ $slot }}
        </div>

        <p class="mt-8 text-sm text-gray-400">© {{ date('Y') }} StudioBook. All rights reserved.</p>
    </div>
</body>

</html>