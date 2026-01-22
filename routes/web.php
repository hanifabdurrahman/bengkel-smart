<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServicePartController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// =====================
// 🔹 Halaman Publik (Bisa diakses siapa saja)
// =====================
Route::get('/', function () {
    // 1. Jika User Sudah Login (Session ada / Remember Me aktif)
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return app(HomeController::class)->index();
})->name('home');

Route::get('/plans', [PlanController::class, 'index'])->name('plans.page');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [HomeController::class, 'login_page'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    // --- Register ---
    Route::get('/register', [HomeController::class, 'register_page'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// =====================
// 🔹 Halaman Member
// =====================
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout.submit');

    // --- LOGIKA LANGGANAN (Proses Checkout) ---
    Route::get('/subscription/checkout/{plan}', [SubscriptionController::class, 'checkout'])
        ->name('subscription.checkout');

    // --- PENGATURAN BENGKEL ---
    Route::get('/settings/profile', [SettingsController::class, 'profile'])->name('settings.profile');
    Route::put('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');

    // --- MANAJEMEN LANGGANAN (INI YANG HILANG SEBELUMNYA) ---
    Route::get('/settings/subscription', [SettingsController::class, 'subscription'])->name('settings.subscription');

    // =====================
    // 🔹 AREA DASHBOARD (Hanya yang sudah berlangganan/aktif)
    // =====================
    Route::middleware(['subscribed'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/service-traffic', [DashboardController::class, 'getServiceTraffic'])->name('dashboard.service-traffic');
        // --- CHATBOT AI ---
        Route::post('/chatbot/send', [ChatbotController::class, 'sendMessage'])->name('chatbot.send');
        // --- CUSTOMERS ---
        // PENTING: Route SEARCH harus ditaruh SEBELUM route {id} / detail / edit
        Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');

        // CRUD Customers Manual
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');

        // --- SPAREPARTS ---
        Route::get('/spareparts', [SparepartController::class, 'index'])->name('spareparts.index');
        Route::get('/spareparts/create', [SparepartController::class, 'create'])->name('spareparts.create');
        Route::post('/spareparts', [SparepartController::class, 'store'])->name('spareparts.store');
        Route::post('/spareparts/{id}/add-stock', [SparepartController::class, 'addStock'])->name('spareparts.addStock');
        Route::get('/spareparts/{id}/edit', [SparepartController::class, 'edit'])->name('spareparts.edit');
        Route::put('/spareparts/{id}', [SparepartController::class, 'update'])->name('spareparts.update');
        Route::delete('/spareparts/{id}', [SparepartController::class, 'destroy'])->name('spareparts.destroy');
        Route::get('/spareparts/{id}', [SparepartController::class, 'show'])->name('spareparts.show');

        // --- SERVICES ---
        Route::resource('services', ServiceController::class);
        // Custom Service Routes
        Route::put('services/{id}/status', [ServiceController::class, 'updateStatus'])->name('services.updateStatus');
        Route::put('services/{id}/jasa', [ServiceController::class, 'updateJasa'])->name('services.updateJasa');

        // --- SERVICE PARTS  ---
        Route::post('service-parts', [ServicePartController::class, 'store'])->name('service-parts.store');
        Route::put('/service-parts/{id}/update-qty', [ServicePartController::class, 'updateQty'])->name('service-parts.updateQty');
        Route::delete('service-parts/{id}', [ServicePartController::class, 'destroy'])->name('service-parts.destroy');
        Route::get('service-part/search', [ServicePartController::class, 'searchAjax'])->name('spareparts.search');
        //-- LIST PAYMENT PENDING ---
        Route::get('/payments/pending', [PaymentController::class, 'pendingList'])->name('payments.pending');

        // --- TRANSACTIONS / PAYMENT ---
        Route::get('transactions/{id}/payment', [TransactionController::class, 'payment'])->name('transactions.payment');
        Route::post('transactions/{id}/process', [TransactionController::class, 'processPayment'])->name('transactions.process');
        Route::get('transactions/{id}/invoice', [TransactionController::class, 'invoice'])->name('transactions.invoice');


        // Route Laporan Keuangan
        Route::get('/reports/revenue', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    }); // End Middleware Subscribed

}); // End Middleware Auth