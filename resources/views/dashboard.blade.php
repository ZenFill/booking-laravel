<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-2xl font-bold text-blue-600 mb-2">Selamat Datang, Windah! 👋</h3>
                <p class="text-gray-600">Ini adalah panel admin untuk mengatur jadwal booking kamu.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <div class="text-sm text-gray-500 mb-1">27 Des 2025</div>
                    <h4 class="font-bold text-lg">Meeting Room A</h4>
                    <p class="text-gray-600 mt-2">Dipesan oleh: Budi</p>
                    <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">
                        Lihat Detail
                    </button>
                </div>

                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <div class="text-sm text-gray-500 mb-1">28 Des 2025</div>
                    <h4 class="font-bold text-lg">Studio Musik</h4>
                    <p class="text-gray-600 mt-2">Dipesan oleh: Siti</p>
                    <button class="mt-4 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 w-full">
                        Setujui
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>