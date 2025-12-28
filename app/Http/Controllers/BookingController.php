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

class BookingController extends Controller
{
    // 1. Menampilkan Daftar Booking Saya
    public function index(Request $request)
    {
        $user = Auth::user(); // Ambil data user yang sedang login

        // 1. Logika "Mata Dewa"
        // Jika Admin: Ambil Query kosong (siap ambil semua).
        // Jika User: Filter berdasarkan ID dia saja.
        if ($user->role === 'admin') {
            $query = Booking::with('user'); // Admin butuh data nama user pemilik booking
        } else {
            $query = Booking::where('user_id', $user->id);
        }

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
        if ($user->role === 'admin') {
            $totalBookings = Booking::count();
            $pendingBookings = Booking::where('status', 'pending')->count();
            $completedBookings = Booking::where('status', 'confirmed')->count();
        } else {
            $totalBookings = Booking::where('user_id', $user->id)->count();
            $pendingBookings = Booking::where('user_id', $user->id)->where('status', 'pending')->count();
            $completedBookings = Booking::where('user_id', $user->id)->where('status', 'confirmed')->count();
        }

        // 5. Bagian Logika Untuk Kalendar
        $allBookingsForCalendar = Booking::all();

        $events = $allBookingsForCalendar->map(function ($booking) {
            // Tentukan warna
            $color = match ($booking->status) {
                'confirmed' => '#10B981',
                'approved' => '#10B981', // Hijau

                'cancelled' => '#EF4444',
                'rejected' => '#EF4444', // Merah
                default => '#F59E0B',    // Kuning
            };

            return [
                'title' => $booking->service_type . ' (' . $booking->name . ')',
                'start' => $booking->booking_date,
                'color' => $color,
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
    public function store(Request $request)
    {
        // 1. VALIDASI DATA (+ Validasi Gambar)
        $request->validate([
            'name' => 'required',
            'service_type' => 'required',
            'booking_date' => 'required|date|after:today',
            'notes' => 'nullable',
            // Validasi File: Harus Gambar (image), format jpeg/png/jpg, maks 2MB (2048 KB)
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. CEK KETERSEDIAAN (Anti-Bentrok) - Kode Lama
        $isBooked = Booking::where('service_type', $request->service_type)
            ->where('booking_date', $request->booking_date)
            ->where('status', '!=', 'cancelled')
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
            'status' => 'pending',
            'payment_proof' => $filePath, // Simpan alamat filenya di sini
        ]);

        // 5. KIRIM EMAIL (Kode Lama)
        try {
            Mail::to('admin@admin.com')->send(new NewBookingNotification($booking));
        } catch (\Exception $e) {
            // Diam saja kalau email gagal
        }

        return redirect()->route('bookings.index')->with('success', 'Booking & Bukti Bayar berhasil dikirim! 🚀');
    }

    // 4. Menampilkan Form Edit (Isinya data lama)
    public function edit(Booking $booking)
    {
        return view('bookings.edit', compact('booking'));
    }

    // 5. Menyimpan Perubahan (Update)
    public function update(Request $request, Booking $booking)
    {
        // Validasi lagi
        $request->validate([
            'name' => 'required',
            'service_type' => 'required',
            'booking_date' => 'required|date',
            'notes' => 'nullable',
        ]);

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
        // Hapus data dari database
        $booking->delete();

        // Balik ke daftar dengan pesan sukses
        return redirect()->route('bookings.index')->with('success', 'Data booking berhasil dihapus!');
    }

    // 7. Aksi Persetujuan Admin
    public function approve(Booking $booking)
    {
        // 1. Cek Admin (Opsional jika sudah pakai middleware, tapi bagus untuk keamanan ganda)
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Anda tidak punya akses.');
        }

        // 2. Ubah status jadi 'confirmed'
        $booking->update(['status' => 'confirmed']);

        // 3. KIRIM EMAIL KE USER (Ini bagian barunya) 📧
        try {
            // Kita kirim ke email milik User yang melakukan booking
            Mail::to($booking->user->email)->send(new \App\Mail\BookingStatusUpdated($booking));
        } catch (\Exception $e) {
            // Jika email gagal, biarkan saja agar tidak error di layar
        }

        return redirect()->back()->with('success', 'Booking disetujui & Email notifikasi terkirim! ✅');
    }

    // 8. Aksi Tolak Admin
    public function reject(Booking $booking)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        // Ubah status jadi 'cancelled'
        $booking->update(['status' => 'cancelled']);

        // KIRIM EMAIL PENOLAKAN
        try {
            Mail::to($booking->user->email)->send(new \App\Mail\BookingStatusUpdated($booking));
        } catch (\Exception $e) {
            // Diam saja
        }

        return redirect()->back()->with('success', 'Booking ditolak & Notifikasi dikirim. ❌');
    }

    // 9. Export ke PDF
    public function exportPdf()
    {
        // Ambil SEMUA data booking (tanpa paginasi)
        // Kalau user biasa, cuma ambil punya dia. Kalau admin, ambil semua.
        if (Auth::user()->role === 'admin') {
            $bookings = Booking::all();
        } else {
            $bookings = Booking::where('user_id', Auth::id())->get();
        }

        // Masukkan data ke view yang baru kita buat
        $pdf = Pdf::loadView('bookings.pdf', compact('bookings'));

        // Download file-nya dengan nama 'laporan-booking.pdf'
        return $pdf->download('laporan-booking.pdf');
    }
}
