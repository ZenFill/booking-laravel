<!DOCTYPE html>
<html>

<head>
    <title>Booking Baru Masuk</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2 style="color: #2563eb;">Halo Admin! 👋</h2>

    <p>Ada pesanan baru masuk ke sistem. Berikut detailnya:</p>

    <div style="background: #f3f4f6; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <p><strong>Nama Pemesan:</strong> {{ $booking->name }}</p>
        <p><strong>Layanan:</strong> {{ $booking->service_type }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y, H:i') }}</p>
        <p><strong>Catatan:</strong> {{ $booking->notes ?? '-' }}</p>
    </div>

    <p>Silakan login ke dashboard untuk menyetujui atau menolak pesanan ini.</p>

    <p style="font-size: 12px; color: #888;">Email otomatis dari Sistem Booking Laravel</p>

</body>

</html>