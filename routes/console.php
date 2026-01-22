<?php

use App\Models\Subscription;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    // 1. Logic update database
    // Gunakan now() agar mengikuti timezone aplikasi
    $count = Subscription::where('status', 'active')
        ->where('date_end', '<', now())
        ->update(['status' => 'expired']);

    // 2. Log hasilnya (Hanya jika ada yang diupdate)
    if ($count > 0) {
        Log::info("SCHEDULER: Berhasil meng-expired-kan {$count} langganan.");
    }
})
    // ->everyMinute()
    ->dailyAt('00:01')
    ->timezone('Asia/Jakarta');
