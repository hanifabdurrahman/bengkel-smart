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
        Schema::create('plans', function (Blueprint $table) {
            // Primary Key (Sesuai error log Anda, foreign key merujuk ke 'plan_id')
            $table->id('plan_id');

            $table->string('plan_name'); // Contoh: "Free Trial", "Pro Monthly"
            $table->decimal('price', 12, 2); // Harga paket
            $table->integer('duration_days')->default(30); // Durasi dalam hari (30, 365, dll)

            // Kolom pelengkap tampilan UI
            $table->text('description')->nullable(); // Deskripsi singkat
            $table->json('features')->nullable(); // List fitur (disimpan sebagai JSON Array)
            $table->boolean('is_popular')->default(false); // Untuk menandai "Best Seller"
            $table->string('badge')->nullable(); // Label promo, misal "Hemat 20%"

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};