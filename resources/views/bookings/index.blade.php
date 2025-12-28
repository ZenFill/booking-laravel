<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Booking') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div
                class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-10 text-white flex justify-between items-center relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl">
                </div>

                <div class="relative z-10">
                    <h1 class="text-3xl font-bold mb-2">Halo, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-blue-100 text-lg">Kelola jadwal booking studio dan ruanganmu dengan mudah di sini.
                    </p>
                </div>
                <div class="relative z-10">
                    <a href="{{ route('bookings.create') }}"
                        class="bg-white text-blue-700 font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-gray-100 transition transform hover:-translate-y-1">
                        + Buat Booking Baru
                    </a>
                    <a href="{{ route('bookings.pdf') }}"
                        class="bg-red-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg hover:bg-red-700 transition transform hover:-translate-y-1 ml-2">
                        📄 Download Laporan
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Booking</p>
                        <h4 class="text-2xl font-bold text-gray-800">{{ $totalBookings }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-400 flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Menunggu Konfirmasi</p>
                        <h4 class="text-2xl font-bold text-gray-800">{{ $pendingBookings }}</h4>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Booking Disetujui</p>
                        <h4 class="text-2xl font-bold text-gray-800">{{ $completedBookings }}</h4>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm mb-10 overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-100 flex justify-between items-center cursor-pointer hover:bg-gray-50 transition"
                    onclick="toggleCalendar()">
                    <div class="flex items-center gap-2">
                        <h3 class="text-lg font-bold text-gray-700">📅 Jadwal Pemakaian Ruangan</h3>
                        <span class="text-xs text-gray-500 bg-blue-50 text-blue-600 px-2 py-1 rounded">Klik untuk
                            lihat</span>
                    </div>
                    <svg id="arrow-icon" class="w-6 h-6 text-gray-400 transform transition-transform duration-300"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>

                <div id="calendar-container" class="hidden transition-all duration-300 ease-in-out">
                    <div class="p-6">
                        <div id="calendar" class="min-h-[500px]"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <h3 class="text-lg font-bold text-gray-700">Riwayat Aktivitas</h3>

                    <form action="{{ route('bookings.index') }}" method="GET" class="flex items-center">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama / layanan..."
                                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm w-64">
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm whitespace-nowrap">
                        <thead class="bg-gray-100 text-gray-600 uppercase tracking-wider font-semibold text-xs">
                            <tr>
                                <th class="px-6 py-4">Layanan</th>
                                <th class="px-6 py-4">Bukti Bayar</th>
                                <th class="px-6 py-4">Pemesan</th>
                                <th class="px-6 py-4">Waktu</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($bookings as $booking)
                                <tr id="row-booking-{{ $booking->id }}" class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold mr-3">
                                                {{ substr($booking->service_type, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800">{{ $booking->service_type }}</div>
                                                <div class="text-xs text-gray-500">ID: #{{ $booking->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->payment_proof)
                                            <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $booking->payment_proof) }}" alt="Bukti"
                                                    class="w-16 h-16 object-cover rounded border hover:scale-150 transition">
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 font-medium">{{ $booking->name }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                            <span
                                                class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($booking->booking_date)->format('H:i') }}
                                                WIB</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="status-pill px-3 py-1 rounded-full text-xs font-bold 
                                                                            {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                                                            {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-700' : '' }}
                                                                            {{ $booking->status == 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center space-x-2 action-buttons">
                                            @if(Auth::user()->role == 'admin' && $booking->status == 'pending')

                                                <button type="button" onclick="confirmApprove({{ $booking->id }})"
                                                    class="p-2 bg-green-50 text-green-600 rounded hover:bg-green-100 transition"
                                                    title="Setujui">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>

                                                <button type="button" onclick="confirmReject({{ $booking->id }})"
                                                    class="p-2 bg-red-50 text-red-600 rounded hover:bg-red-100 transition"
                                                    title="Tolak Booking">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>

                                            @endif
                                            <a href="{{ route('bookings.edit', $booking->id) }}"
                                                class="p-2 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition"
                                                title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <button type="button" onclick="confirmDelete({{ $booking->id }})"
                                                class="p-2 bg-red-50 text-red-600 rounded hover:bg-red-100 transition"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if($bookings->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col justify-center items-center">

                                            <svg class="w-40 h-40 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>

                                            <h3 class="text-lg font-medium text-gray-900">Belum ada booking</h3>
                                            <p class="text-gray-500 mt-1 mb-6">Mulai buat jadwal pertamamu atau tunggu
                                                pesanan masuk.</p>

                                            <a href="{{ route('bookings.create') }}"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                + Buat Booking Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $bookings->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<!-- Custom Calendar Styles -->
<style>
    /* Header Styling */
    .fc-toolbar-title {
        font-family: 'Figtree', sans-serif !important;
        font-weight: 800 !important;
        color: #1e3a8a !important;
        /* Blue 900 */
        font-size: 1.5rem !important;
        letter-spacing: -0.025em;
    }

    /* Button Styling */
    .fc-button-primary {
        background-color: #3b82f6 !important;
        /* Blue 500 */
        border-color: #3b82f6 !important;
        border-radius: 0.5rem !important;
        /* Rounded-lg */
        font-weight: 600 !important;
        text-transform: capitalize;
        padding: 0.5rem 1rem !important;
        transition: all 0.2s;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
    }

    .fc-button-primary:hover {
        background-color: #2563eb !important;
        /* Blue 600 */
        border-color: #2563eb !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 8px -1px rgba(59, 130, 246, 0.3);
    }

    .fc-button-primary:disabled {
        background-color: #93c5fd !important;
        border-color: #93c5fd !important;
    }

    /* Active State for View Buttons */
    .fc-button-active {
        background-color: #1e40af !important;
        /* Blue 800 */
        border-color: #1e40af !important;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05) !important;
    }

    /* Current Day Highlight */
    .fc-day-today {
        background-color: #eff6ff !important;
        /* Blue 50 */
    }

    /* Header Cells */
    .fc-col-header-cell-cushion {
        padding: 12px 0 !important;
        color: #4b5563;
        /* Gray 600 */
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.05em;
    }

    /* Event Styling */
    .fc-event {
        border-radius: 6px !important;
        border: none !important;
        padding: 2px 4px !important;
        font-size: 0.85rem !important;
        font-weight: 600 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s;
    }

    .fc-event:hover {
        transform: scale(1.02);
        cursor: pointer;
    }
</style>

<script>
    // --- 1. Fungsi AJAX Helper ---
    async function performAction(url, method, confirmTitle, confirmText, confirmButtonText, confirmColor = '#d33', isDelete = false, bookingId) {
        const result = await Swal.fire({
            title: confirmTitle,
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    // body: JSON.stringify({}) 
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire('Berhasil!', data.message, 'success');

                    if (isDelete) {
                        const row = document.getElementById('row-booking-' + bookingId);
                        if (row) row.remove();
                    } else {
                        // Refresh halaman untuk update status pill (opsional bisa manipulasi DOM jika mau lebih canggih)
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                }
            } catch (error) {
                Swal.fire('Error!', 'Terjadi kesalahan jaringan.', 'error');
                console.error(error);
            }
        }
    }

    // --- 2. Action Wrappers ---
    function confirmDelete(bookingId) {
        performAction(
            `/bookings/${bookingId}`,
            'DELETE',
            'Apakah Anda yakin?',
            'Data booking ini akan dihapus permanen!',
            'Ya, Hapus!',
            '#d33',
            true,
            bookingId
        );
    }

    function confirmReject(bookingId) {
        performAction(
            `/bookings/${bookingId}/reject`,
            'PATCH',
            'Tolak Booking ini?',
            'Status akan berubah menjadi Cancelled.',
            'Ya, Tolak!',
            '#d33',
            false,
            bookingId
        );
    }

    function confirmApprove(bookingId) {
        performAction(
            `/bookings/${bookingId}/approve`,
            'PATCH',
            'Setujui Booking ini?',
            'Status akan berubah menjadi Confirmed.',
            'Ya, Setujui!',
            '#10b981', // Green
            false,
            bookingId
        );
    }

    // --- 3. Calendar Logic ---
    var calendar;

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        if (calendarEl) {
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'standard',
                locale: 'id',
                height: 'auto',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: 'Hari Ini', month: 'Bulan', week: 'Minggu', list: 'List'
                },
                events: @json($events),
                eventClick: function (info) {
                    info.jsEvent.preventDefault(); // Prevent Browser Navigation
                    info.jsEvent.stopPropagation(); // Prevent Event Bubbling

                    const eventColor = info.event.backgroundColor;

                    Swal.fire({
                        title: '<span class="text-xl font-bold text-gray-800">' + info.event.title + '</span>',
                        html: `
                            <div class="text-left bg-gray-50 p-4 rounded-lg border border-gray-100 mt-2">
                                <p class="mb-2"><span class="font-semibold text-gray-500 w-20 inline-block">Waktu:</span> <span class="text-gray-900 font-medium">${info.event.start.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })} WIB</span></p>
                                <p class="mb-2"><span class="font-semibold text-gray-500 w-20 inline-block">Tanggal:</span> <span class="text-gray-900 font-medium">${info.event.start.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}</span></p>
                            </div>
                            <p class="mt-4 text-sm text-gray-400">Klik tombol di bawah untuk menutup</p>
                        `,
                        icon: 'info',
                        iconColor: eventColor,
                        confirmButtonText: 'Tutup',
                        confirmButtonColor: '#3b82f6',
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'rounded-lg px-6 py-2.5 font-bold shadow-md'
                        },
                        buttonsStyling: false,
                        focusConfirm: false, // Prevent auto-focusing that might cause issues on Enter
                        returnFocus: false // Prevent returning focus to the element which might trigger weird browser behaviors
                    });
                }
            });

            // Initial render
            calendar.render();
        }
    });

    // --- 4. Toggle Calendar ---
    function toggleCalendar() {
        const container = document.getElementById('calendar-container');
        const arrow = document.getElementById('arrow-icon');

        if (!container || !arrow) return;

        container.classList.toggle('hidden');
        if (container.classList.contains('hidden')) {
            arrow.classList.remove('rotate-180');
        } else {
            arrow.classList.add('rotate-180');
            if (calendar) {
                setTimeout(() => { calendar.updateSize(); }, 10);
            }
        }
    }
</script>