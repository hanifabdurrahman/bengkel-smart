<?php

use App\Http\Controllers\Api\MidtransCallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/payments/midtrans-notification', [MidtransCallbackController::class, 'handle'])
    ->name('api.midtrans.notification');