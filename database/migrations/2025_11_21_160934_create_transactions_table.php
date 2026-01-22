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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('transaction_id');

            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('customer_id')->nullable();

            $table->date('tanggal');
            $table->enum('jenis_transaksi', ['servis', 'penjualan', 'servis+penjualan']);

            $table->decimal('total_sparepart', 12, 2)->default(0);
            $table->decimal('total_jasa', 12, 2)->default(0);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('total_akhir', 12, 2)->default(0);

            $table->enum('status_pembayaran', ['pending', 'lunas', 'dp'])->default('pending');

            $table->timestamps();

            $table->foreign('workshop_id')
                ->references('workshop_id')->on('workshops')
                ->onDelete('cascade');

            $table->foreign('customer_id')
                ->references('customer_id')->on('customers')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
