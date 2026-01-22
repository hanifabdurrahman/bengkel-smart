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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('subscription_id');

            $table->unsignedBigInteger('workshop_id');
            $table->unsignedBigInteger('plan_id');

            $table->date('date_start');
            $table->date('date_end');
            $table->enum('status', ['active', 'pending', 'expired', 'cancelled'])->default('active');

            $table->timestamps();

            $table->foreign('workshop_id')
                ->references('workshop_id')->on('workshops')
                ->onDelete('cascade');

            $table->foreign('plan_id')
                ->references('plan_id')->on('plans')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};