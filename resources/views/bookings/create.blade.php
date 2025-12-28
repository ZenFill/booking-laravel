<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Booking Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pemesan</label>
                        <input type="text" name="name"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required placeholder="Contoh: Windah Basudara">
                    </div>

                    <div class="mb-4">
                        <x-input-label for="service_type" :value="__('Pilih Layanan / Ruangan')" />

                        <select id="service_type" name="service_type"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required>
                            <option value="" disabled selected>-- Klik untuk memilih --</option>

                            @foreach($services as $service)
                                <option value="{{ $service->name }}" {{ old('service_type') == $service->name ? 'selected' : '' }}>
                                    {{ $service->name }} (Rp {{ number_format($service->price, 0, ',', '.') }})
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('service_type')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal & Jam</label>
                        <input type="datetime-local" name="booking_date"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                        @error('booking_date')
                            <p class="text-red-500 text-sm mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="3"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="payment_proof" :value="__('Bukti Transfer (Screenshot/Foto)')" />
                        
                        <input id="payment_proof" type="file" name="payment_proof" 
                            class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            accept="image/*" required>
                        
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, JPEG. Maksimal 2MB.</p>
                        <x-input-error :messages="$errors->get('payment_proof')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('bookings.index') }}"
                            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan
                            Booking</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>