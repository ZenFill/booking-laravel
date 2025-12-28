<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Layanan / Ruangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-8 bg-gray-50 p-4 rounded border">
                    <h3 class="font-bold text-lg mb-4">Tambah Ruangan Baru</h3>
                    <form action="{{ route('services.store') }}" method="POST" class="flex gap-4 items-end">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-gray-700">Nama Ruangan</label>
                            <input type="text" name="name" class="w-full border-gray-300 rounded" required placeholder="Contoh: Gaming Room">
                        </div>
                        <div class="w-40">
                            <label class="block text-sm font-bold text-gray-700">Harga (Rp)</label>
                            <input type="number" name="price" class="w-full border-gray-300 rounded" required placeholder="0">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-bold">
                            + Simpan
                        </button>
                    </form>
                </div>

                <table class="min-w-full text-left text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-2">Nama Layanan</th>
                            <th class="px-4 py-2">Harga</th>
                            <th class="px-4 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($services as $service)
                        <tr>
                            <td class="px-4 py-3 font-bold">{{ $service->name }}</td>
                            <td class="px-4 py-3">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center">
                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('Hapus ruangan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>