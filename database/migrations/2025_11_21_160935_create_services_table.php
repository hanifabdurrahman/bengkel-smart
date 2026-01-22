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
            $table->id('service_id');

            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('transaction_id')->nullable();

            $table->string('kode_servis', 30)->unique();
            $table->date('tanggal_masuk');
            $table->dateTime('waktu_selesai')->nullable();
            $table->string('jenis_servis', 100)->nullable();
            $table->text('keluhan')->nullable();
            $table->decimal('biaya_jasa', 12, 2)->default(0);
            $table->enum('status', ['antri', 'proses', 'selesai', 'batal'])->default('antri');

            $table->timestamps();

            $table->foreign('workshop_id')
                ->references('workshop_id')->on('workshops')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('customer_id')->on('customers')
                ->onDelete('restrict');

            $table->foreign('transaction_id')
                ->references('transaction_id')->on('transactions')
                ->onDelete('set null');
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