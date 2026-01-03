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
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;

class BookingController extends Controller
{
    /**
     * Helper to send email notifications with error handling.
     *
     * @param string $recipient
     * @param mixed $mailable
     * @return void
     */
    protected function sendNotification($recipient, $mailable)
    {
        try {
            Mail::to($recipient)->send($mailable);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of bookings.
     * Includes logic for search, pagination, statistics, and calendar events.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Data Retrieval Scope
        $query = Booking::forUser($user)->with('user');

        // 2. Search Logic
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('service_type', 'like', "%{$search}%");
            });
        }

        // 3. Pagination
        $bookings = $query->latest()->paginate(5);

        // 4. Statistics
        $statsQuery = Booking::forUser($user);
        $totalBookings = (clone $statsQuery)->count();
        $pendingBookings = (clone $statsQuery)->where('status', Booking::STATUS_PENDING)->count();
        $completedBookings = (clone $statsQuery)->where('status', Booking::STATUS_CONFIRMED)->count();

        // 5. Calendar Events
        $allBookingsForCalendar = Booking::all();
        $events = $allBookingsForCalendar->map(function ($booking) {
            return [
                'title' => $booking->service_type . ' (' . $booking->name . ')',
                'start' => $booking->booking_date,
                'color' => $booking->color,
            ];
        });

        return view('bookings.index', compact('bookings', 'totalBookings', 'pendingBookings', 'completedBookings', 'events'));
    }

    /**
     * Show the form for creating a new booking.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $services = Service::where('is_active', true)->get();
        return view('bookings.create', compact('services'));
    }

    /**
     * Store a newly created booking in storage.
     *
     * @param StoreBookingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBookingRequest $request)
    {
        // 1. Check Availability
        $isBooked = Booking::where('service_type', $request->service_type)
            ->where('booking_date', $request->booking_date)
            ->where('status', '!=', Booking::STATUS_CANCELLED)
            ->exists();

        if ($isBooked) {
            return back()->withInput()->withErrors(['booking_date' => 'Maaf, ruangan & jam ini sudah dibooking.']);
        }

        // 2. Handle File Upload
        $filePath = null;
        if ($request->hasFile('payment_proof')) {
            $filePath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        // 3. Create Booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'service_type' => $request->service_type,
            'booking_date' => $request->booking_date,
            'notes' => $request->notes,
            'status' => Booking::STATUS_PENDING,
            'payment_proof' => $filePath,
        ]);

        // 4. Send Admin Notification
        $adminEmail = config('mail.admin_address', env('ADMIN_EMAIL', 'admin@admin.com'));
        $this->sendNotification($adminEmail, new NewBookingNotification($booking));

        return redirect()->route('bookings.index')->with('success', 'Booking & Bukti Bayar berhasil dikirim.');
    }

    /**
     * Show the form for editing the specified booking.
     *
     * @param Booking $booking
     * @return \Illuminate\View\View
     */
    public function edit(Booking $booking)
    {
        // Access Control
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak untuk mengedit booking ini.');
        }

        return view('bookings.edit', compact('booking'));
    }

    /**
     * Update the specified booking in storage.
     *
     * @param UpdateBookingRequest $request
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        // Access Control is handled in FormRequest
        $booking->update([
            'name' => $request->name,
            'service_type' => $request->service_type,
            'booking_date' => $request->booking_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('bookings.index')->with('success', 'Data booking berhasil diperbarui.');
    }

    /**
     * Remove the specified booking from storage.
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Booking $booking)
    {
        // Access Control
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'Anda tidak berhak menghapus booking ini.');
        }

        $booking->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Data booking berhasil dihapus!']);
        }

        return redirect()->route('bookings.index')->with('success', 'Data booking berhasil dihapus!');
    }

    /**
     * Approve the specified booking (Admin only).
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function approve(Booking $booking)
    {
        if (!Auth::user()->isAdmin()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Anda tidak punya akses.'], 403);
            }
            abort(403, 'Anda tidak punya akses.');
        }

        $booking->update(['status' => Booking::STATUS_CONFIRMED]);

        $this->sendNotification($booking->user->email, new BookingStatusUpdated($booking));

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking disetujui & Email notifikasi terkirim.']);
        }

        return redirect()->back()->with('success', 'Booking disetujui & Email notifikasi terkirim.');
    }

    /**
     * Reject the specified booking (Admin only).
     *
     * @param Booking $booking
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reject(Booking $booking)
    {
        if (!Auth::user()->isAdmin()) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Anda tidak punya akses.'], 403);
            }
            abort(403);
        }

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        $this->sendNotification($booking->user->email, new BookingStatusUpdated($booking));

        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Booking ditolak & Notifikasi dikirim.']);
        }

        return redirect()->back()->with('success', 'Booking ditolak & Notifikasi dikirim.');
    }

    /**
     * Download booking report as PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $bookings = Booking::with('user')->latest()->get();
            $title = 'Laporan Seluruh Booking (Admin)';
        } else {
            $bookings = Booking::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
            $title = 'Riwayat Booking Saya';
        }

        $pdf = Pdf::loadView('bookings.pdf', compact('bookings', 'title'));

        return $pdf->download('laporan-booking.pdf');
    }
}
