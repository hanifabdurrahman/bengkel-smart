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
        Schema::table('workshops', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('email');
            $table->string('password')->nullable()->change(); // Password boleh kosong jika login via Google

            // Ubah kolom ini jadi nullable agar tidak error saat register via Google
            $table->string('phone_number')->nullable()->change();
            $table->text('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn('google_id');
            // Kembalikan ke not null jika perlu (hati-hati data lama)
            // $table->string('password')->nullable(false)->change();
        });
    }
};
