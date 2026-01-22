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
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id('sparepart_id');
            $table->unsignedBigInteger('workshop_id');

            $table->string('sparepart_code', 50);
            $table->string('sparepart_name', 150);
            $table->integer('stock_quantity')->default(0);
            $table->decimal('buying_price', 15, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->string('rack_location', 50)->nullable();
            $table->date('entry_date')->nullable();
            $table->timestamps();

            $table->foreign('workshop_id')
                ->references('workshop_id')->on('workshops')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spareparts');
    }
};