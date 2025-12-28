<!DOCTYPE html>
<html>

<head>
    <title>Update Status Booking</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2>Halo, {{ $booking->name }}! 👋</h2>

    <p>Ada kabar terbaru mengenai booking kamu.</p>

    <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h3 style="margin-top: 0;">Status Terbaru:
            @if($booking->status == 'confirmed')
                <span style="color: green;">DISETUJUI ✅</span>
            @elseif($booking->status == 'cancelled')
                <span style="color: red;">DITOLAK ❌</span>
            @else
                {{ ucfirst($booking->status) }}
            @endif
        </h3>

        <hr style="border: 0; border-top: 1px solid #ddd;">

        <p><strong>Layanan:</strong> {{ $booking->service_type }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y, H:i') }} WIB</p>
    </div>

    @if($booking->status == 'confirmed')
        <p>Silakan datang tepat waktu ya! Terima kasih telah menggunakan layanan kami.</p>
    @elseif($booking->status == 'cancelled')
        <p>Mohon maaf atas ketidaknyamanan ini. Silakan hubungi admin atau buat booking di tanggal lain.</p>
    @endif

    <p style="font-size: 12px; color: #888; margin-top: 30px;">Email otomatis dari Sistem Booking.</p>

</body>

</html>