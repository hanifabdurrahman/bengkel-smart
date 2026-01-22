<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        Log::info("===== CHATBOT REQUEST MASUK =====");
        Log::info("User input:", ['message' => $request->message]);

        try {
            $request->validate([
                'message' => 'required|string',
            ]);
        } catch (\Exception $e) {
            Log::error("VALIDATION ERROR: " . $e->getMessage());
            return response()->json(['reply' => 'Input tidak valid.'], 422);
        }

        $userMessage = $request->message;

        // Ganti dengan API Key yang sesuai (Gemini / OpenAI)
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            Log::error("API KEY TIDAK DITEMUKAN");
            return response()->json(['reply' => 'Error: API Key belum dikonfigurasi.'], 500);
        }

        // === INOVASI 1: AMBIL DATA REAL-TIME BENGKEL ===
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        // Data Stok Menipis
        $lowStockItems = $workshop->spareparts()
            ->where('stock_quantity', '<=', 5)
            ->limit(5) // Ambil 5 teratas
            ->get()
            ->map(function ($item) {
                return "- {$item->name} (Sisa: {$item->stock_quantity} {$item->unit})";
            })
            ->implode("\n");

        // Data Keuangan Hari Ini
        // ... inside sendMessage method ...

        // Data Keuangan Hari Ini (Updated Logic)
        $todayIncome = $workshop->transactions()
            // Check for both 'completed' (from your ChatbotController) and 'lunas' (from TransactionController)
            ->whereIn('status_pembayaran', ['completed', 'lunas'])
            // Use updated_at to capture when the payment actually happened
            ->whereDate('updated_at', Carbon::today())
            ->sum('total_akhir');

        // ... rest of the code ...

        // ...
        // Data Keuangan Hari Ini
        $todayIncome = $workshop->transactions()
            ->whereIn('status_pembayaran', ['lunas', 'completed']) // Handle both just in case
            ->whereDate('updated_at', Carbon::today()) // Use updated_at for payment time
            ->sum('total_akhir');

        // Data Antrian Servis (Pending or Process)
        $pendingCount = $workshop->transactions()
            // Ensure these match your database enums exactly
            ->whereIn('status_pembayaran', ['pending', 'process', 'antri'])
            ->count();
        // ...



        // PERBAIKAN: Ambil list semua sparepart (sebagai string) sebelum dimasukkan ke teks
        $allSpareparts = $workshop->spareparts()
            ->pluck('sparepart_name')
            ->implode(', ');

        if (empty($allSpareparts)) {
            $allSpareparts = "Belum ada data sparepart.";
        }

        // === INOVASI 2: KONTEKS SUPER CERDAS (UPDATED) ===
        $systemContext = "
        Kamu adalah 'BengkelSmart AI', asisten manajer bengkel profesional.

        DATA REAL-TIME BENGKEL SAAT INI:
        1. Nama Bengkel: {$workshop->workshop_name}
        2. Pendapatan Masuk Hari Ini: Rp " . number_format($todayIncome, 0, ',', '.') . "
           (Catatan: Ini adalah uang tunai/transfer yang diterima kasir hari ini).
        3. Antrian/Servis Sedang Berjalan: {$pendingCount} kendaraan.

        4. STATUS STOK KRITIS (Prioritas Restock):
        " . ($lowStockItems ?: '- Stok aman, semua terkendali.') . "

        5. DAFTAR SEMUA SPAREPART DI GUDANG:
        " . $allSpareparts . "

        INSTRUKSI KHUSUS:
        - Jika user bertanya 'Berapa omset hari ini?', jawab dengan data Pendapatan di atas.
        - Jika user bertanya 'Apa yang harus saya beli?', cek data STATUS STOK KRITIS.
        - Jika user bertanya apakah sparepart X tersedia, cek DAFTAR SEMUA SPAREPART.
        - Jawab dengan singkat, profesional, dan membantu layaknya manajer bengkel.

        INSTRUKSI:
        - Jawab dengan singkat, padat, dan ramah.
        - Jika user bertanya 'Apa yang harus saya lakukan?', berikan saran berdasarkan data di atas (misal: sarankan restock jika ada stok menipis, atau fokus selesaikan antrian).
        - Gunakan formatting sederhana seperti point-point jika perlu.
        - Jika tidak tahu jawabannya, katakan 'Maaf, saya tidak mengerti.
        - Jangan buat jawaban berdasarkan asumsi atau data di luar konteks bengkel ini.
        ";

        try {
            // Request ke Gemini API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            ['text' => $systemContext . "\n\nUser bertanya: " . $userMessage]
                        ]
                    ]
                ]
            ]);


            if ($response->successful()) {
                $data = $response->json();
                $botReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti.';

                // Format Markdown ke HTML (Bold & Newline)
                $botReply = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $botReply); // Bold
                $botReply = str_replace("* ", "<br>• ", $botReply); // List item
                $botReply = nl2br($botReply); // Newlines

                return response()->json(['reply' => $botReply]);
            } else {
                Log::error('AI Error: ' . $response->body());
                return response()->json(['reply' => 'Sistem AI sedang sibuk, coba lagi nanti.'], 500);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['reply' => 'Terjadi kesalahan jaringan.'], 500);
        }
    }
}