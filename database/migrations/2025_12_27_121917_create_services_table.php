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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Studio Musik"
            $table->text('description')->nullable(); // Penjelasan singkat
            $table->decimal('price', 10, 2)->default(0); // Harga per sesi
            $table->boolean('is_active')->default(true); // Status: Aktif/Renovasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
