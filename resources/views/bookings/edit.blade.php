<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT') <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pemesan</label>
                        <input type="text" name="name" value="{{ $booking->name }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Layanan</label>
                        <select name="service_type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Meeting Room A" {{ $booking->service_type == 'Meeting Room A' ? 'selected' : '' }}>Meeting Room A</option>
                            <option value="Studio Musik" {{ $booking->service_type == 'Studio Musik' ? 'selected' : '' }}>Studio Musik</option>
                            <option value="Lapangan Futsal" {{ $booking->service_type == 'Lapangan Futsal' ? 'selected' : '' }}>Lapangan Futsal</option>
                            <option value="Katering" {{ $booking->service_type == 'Katering' ? 'selected' : '' }}>Katering</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal & Jam</label>
                        <input type="datetime-local" name="booking_date" value="{{ $booking->booking_date }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Tambahan</label>
                        <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $booking->notes }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('bookings.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Update Data</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>