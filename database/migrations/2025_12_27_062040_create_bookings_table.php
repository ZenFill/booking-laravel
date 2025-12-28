<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Agar tahu siapa yang booking (User Login)
            $table->string('name'); // Nama pemesan (bisa beda dengan nama akun)
            $table->string('service_type'); // Jenis layanan (misal: Meeting Room, Studio, Lapangan)
            $table->dateTime('booking_date'); // Tanggal dan Jam booking
            $table->text('notes')->nullable(); // Catatan tambahan (opsional)
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending'); // Status booking
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
