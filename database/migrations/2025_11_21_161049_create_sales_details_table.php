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
        Schema::create('sales_details', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('sparepart_id');

            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('sub_total', 12, 2);

            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('transaction_id')->on('transactions')
                ->onDelete('cascade');

            $table->foreign('sparepart_id')
                ->references('sparepart_id')->on('spareparts')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_details');
    }
};