<?php

namespace App\Http\Controllers;

use App\Mail\BookingStatusUpdated;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewBookingNotification;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Log; // Import Log
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;

class BookingController extends Controller
{
    // Fungsi bantu untuk kirim email dengan error handling
    protected function sendNotification($recipient, $mailable)
    {
        try {
            Mail::to($recipient)->send($mailable);
        } catch (\Exception $e) {
            // Log error agar admin tahu ada masalah email, tapi jangan crash aplikasi
            Log::error('Gagal mengirim email: ' . $e->getMessage());
        }
    }

    // 1. Menampilkan Daftar Booking Saya

    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil data user yang sedang login

        // 1. Logika "Mata Dewa" & User Biasa (Refactored using Scope)
        $query = Booking::forUser($user)->with('user');


        // 2. Pencarian (Search) - Sama seperti sebelumnya
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('service_type', 'like', "%{$search}%");
            });
        }

        // 3. Ambil Data (Pagination)
        $bookings = $query->latest()->paginate(5);

        // 4. Statistik Pintar (Sesuaikan dengan Role)
        // 4. Statistik Pintar (Sesuaikan dengan Role)
        // Kita bisa pakai query builder terpisah agar lebih bersih
        $statsQuery = Booking::forUser($user);

        $totalBookings = (clone $statsQuery)->count();
        $pendingBookings = (clone $statsQuery)->where('status', Booking::STATUS_PENDING)->count();
        $completedBookings = (clone $statsQuery)->where('status', Booking::STATUS_CONFIRMED)->count();

        // 5. Bagian Logika Untuk Kalendar
        $allBookingsForCalendar = Booking::all();

        $events = $allBookingsForCalendar->map(function ($booking) {
            return [
                'title' => $booking->service_type . ' (' . $booking->name . ')',
                'start' => $booking->booking_date,
                'color' => $booking->color, // Validasi warna sudah di Model (Accessor)
            ];
        });

        return view('bookings.index', compact('bookings', 'totalBookings', 'pendingBookings', 'completedBookings', 'events'));
    }

    // 2. Menampilkan Formulir Tambah Booking
    public function create()
    {
        // Ambil layanan yang statusnya aktif saja
        $services = Service::where('is_active', true)->get();

        // Kirim variable $services ke view
        return view('bookings.create', compact('services'));
    }

    // 3. Menyimpan Data Booking ke Database
    public function store(StoreBookingRequest $request)
    {
        // 1. VALIDASI DATA (Sudah ditangani oleh StoreBookingRequest)

        // 2. CEK KETERSEDIAAN (Anti-Bentrok)
        $isBooked = Booking::where('service_type', $request->service_type)
            ->where('booking_date', $request->booking_date)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->exists();

        if ($isBooked) {
            return back()->withInput()->withErrors(['booking_date' => 'Maaf, ruangan & jam ini sudah dibooking! ⛔']);
        }

        // 3. PROSES UPLOAD GAMBAR (Baru!) 📸
        $filePath = null; // Siapkan variabel kosong
        if ($request->hasFile('payment_proof')) {
            // Simpan file ke folder 'public/payment_proofs'
            // Laravel otomatis akan membuatkan nama file unik acak
            $filePath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // 4. SIMPAN KE DATABASE ✅
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'service_type' => $request->service_type,
            'booking_date' => $request->booking_date,
            'notes' => $request->notes,
            'status' => Booking::STATUS_PENDING,
            'payment_proof' => $filePath, // Simpan alamat filenya di sini
        ]);

        // 5. KIRIM EMAIL (Refactored)
        // Ambil email admin dari config/env (jangan hardcoded)
        $adminEmail = config('mail.admin_address', env('ADMIN_EMAIL', 'admin@admin.com'));
        $this->sendNotification($adminEmail, new NewBookingNotification($booking));

        return redirect()->route('bookings.index')->with('success', 'Booking & Bukti Bayar berhasil dikirim! 🚀');
    }

    // 4. Menampilkan Form Edit (Isinya data lama)
    public function edit(Booking $booking)
    {
        // CEK KEPEMILIKAN: Hanya admin atau pemilik yg boleh edit
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit booking ini.');
        }

        return view('bookings.edit', compact('booking'));
    }

    // 5. Menyimpan Perubahan (Update)
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // Validasi sudah ditangani UpdateBookingRequest

        // Update data di database
        $booking->update([
            'name' => $request->name,
            'service_type' => $request->service_type,
            'booking_date' => $request->booking_date,
            'notes' => $request->notes,
        ]);

        // Balik ke halaman daftar
        return redirect()->route('bookings.index')->with('success', 'Data booking berhasil diperbarui! ✨');
    }

    // 6. Menghapus Data (Delete)
    public function destroy(Booking $booking)
    {
        // CEK KEPEMILIKAN: Hanya admin atau pemilik yg boleh hapus
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'Anda tidak berhak menghapus booking ini.');
        }

        // Hapus data dari database
        $booking->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data booking berhasil dihapus!']);
        }

        // Balik ke daftar dengan pesan sukses
        return redirect()->route('bookings.index')->with('success', 'Data booking berhasil dihapus!');
    }

    // 7. Aksi Persetujuan Admin
    public function approve(Booking $booking)
    {
        if (!Auth::user()->isAdmin()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Anda tidak punya akses.'], 403);
            }
            abort(403, 'Anda tidak punya akses.');
        }

        $booking->update(['status' => Booking::STATUS_CONFIRMED]);

        // KIRIM EMAIL KE USER
        $this->sendNotification($booking->user->email, new BookingStatusUpdated($booking));

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking disetujui & Email notifikasi terkirim! ✅']);
        }

        return redirect()->back()->with('success', 'Booking disetujui & Email notifikasi terkirim! ✅');
    }

    // 8. Aksi Tolak Admin
    public function reject(Booking $booking)
    {
        if (!Auth::user()->isAdmin()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Anda tidak punya akses.'], 403);
            }
            abort(403);
        }

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        // KIRIM EMAIL PENOLAKAN
        $this->sendNotification($booking->user->email, new BookingStatusUpdated($booking));

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking ditolak & Notifikasi dikirim. ❌']);
        }

        return redirect()->back()->with('success', 'Booking ditolak & Notifikasi dikirim. ❌');
    }

    // 9. Export ke PDF
    public function downloadPdf()
    {
        $user = Auth::user(); // Cek siapa yang klik tombol download

        // LOGIKA FILTER (Mata Dewa vs Mata Manusia)
        if ($user->role === 'admin') {
            // Jika Admin: Ambil SEMUA data, urutkan dari yang terbaru
            $bookings = Booking::with('user')->latest()->get();
            $title = 'Laporan Seluruh Booking (Admin)';
        } else {
            // Jika User Biasa: Ambil data punya DIA SAJA
            $bookings = Booking::with('user')
                ->where('user_id', $user->id) // <--- KUNCI PENGAMANAN 🔐
                ->latest()
                ->get();
            $title = 'Riwayat Booking Saya';
        }

        // Generate PDF dengan data yang sudah disaring
        $pdf = Pdf::loadView('bookings.pdf', compact('bookings', 'title'));

        return $pdf->download('laporan-booking.pdf');
    }
}
