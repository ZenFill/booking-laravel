<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StudioBook - Booking Ruangan Mudah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-800">

    <nav class="bg-white/80 backdrop-blur-md shadow-sm fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="bg-blue-600 text-white p-2 rounded-xl shadow-lg shadow-blue-500/30">
                        <!-- Gunakan SVG yang sama dengan ApplicationLogo tapi inline jika perlu, atau ganti icon -->
                        <svg viewBox="0 0 24 24" fill="none" class="w-8 h-8" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 2V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 2V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.5 9.09H20.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="font-extrabold text-2xl text-gray-800 tracking-tight">Studio<span class="text-blue-600">Book</span></span>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/bookings') }}"
                                class="text-gray-700 hover:text-blue-600 font-bold transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-600 hover:text-blue-600 font-bold transition hover:scale-105 transform">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="bg-blue-600 text-white px-6 py-2.5 rounded-full font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 hover:-translate-y-1 transform">Daftar Sekarang</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative bg-gradient-to-br from-blue-700 via-indigo-700 to-blue-900 text-white pt-40 pb-32 px-6 overflow-hidden">
        <div class="absolute top-20 right-0 -mt-20 -mr-20 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-blue-400 opacity-10 rounded-full blur-3xl animate-float"></div>

        <div class="max-w-4xl mx-auto text-center relative z-10">
            <span
                class="bg-blue-500/30 text-blue-100 px-6 py-2 rounded-full text-sm font-bold mb-8 inline-block backdrop-blur-md border border-blue-400/30 shadow-lg">
                🚀 Solusi Booking Ruangan No. #1
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold mb-8 leading-tight tracking-tight drop-shadow-lg">
                Kelola Jadwal & Sewa <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-white">Ruangan Lebih Mudah</span>
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-12 max-w-2xl mx-auto leading-relaxed">
                Booking Studio Musik, Lapangan Futsal, atau Ruang Meeting kapan saja. Tanpa ribet, langsung dikonfirmasi otomatis.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-5">
                <a href="{{ route('register') }}"
                    class="bg-white text-blue-700 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-blue-50 transition shadow-xl shadow-blue-900/20 transform hover:-translate-y-1 hover:scale-105 active:scale-95 duration-200">
                    Mulai Booking Gratis
                </a>
                <a href="#services"
                    class="backdrop-blur-sm bg-white/10 border border-white/30 text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-white/20 transition hover:border-white/50">
                    Lihat Layanan
                </a>
            </div>
        </div>
    </div>

    <div id="services" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight">Layanan Tersedia</h2>
                <p class="text-gray-500 mt-4 text-lg">Pilih ruangan terbaik sesuai kebutuhan aktivitasmu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Card 1 -->
                <div class="bg-white p-10 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 group hover:-translate-y-2">
                    <div
                        class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-3 transition duration-300 shadow-sm">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-purple-600 transition">Studio Musik</h3>
                    <p class="text-gray-500 leading-relaxed">Peralatan lengkap, kedap suara, dan sound system premium untuk latihan band profesional.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white p-10 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 group hover:-translate-y-2">
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-3 transition duration-300 shadow-sm">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-green-600 transition">Lapangan Futsal</h3>
                    <p class="text-gray-500 leading-relaxed">Rumput sintetis standar FIFA, pencahayaan optimal, dan fasilitas kamar ganti bersih.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-10 rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 group hover:-translate-y-2">
                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-3 transition duration-300 shadow-sm">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 group-hover:text-blue-600 transition">Ruang Meeting</h3>
                    <p class="text-gray-500 leading-relaxed">Dilengkapi Proyektor HD, AC dingin, dan whiteboard. Cocok untuk rapat tim atau workshop.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-center md:text-left">
                <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                    <div class="bg-blue-600 text-white p-1.5 rounded-lg">
                         <svg viewBox="0 0 24 24" fill="none" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 2V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 2V5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3.5 9.09H20.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 8.5V17C21 20 19.5 22 16 22H8C4.5 22 3 20 3 17V8.5C3 5.5 4.5 3.5 8 3.5H16C19.5 3.5 21 5.5 21 8.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="font-bold text-white text-xl">StudioBook</span>
                </div>
                <p class="text-sm">© {{ date('Y') }} Hak Cipta Dilindungi.</p>
            </div>
            <div class="flex space-x-8 font-medium">
                <a href="#" class="hover:text-white transition hover:underline">Kebijakan Privasi</a>
                <a href="#" class="hover:text-white transition hover:underline">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-white transition hover:underline">Kontak</a>
            </div>
        </div>
    </footer>

</body>

</html>