<?php

namespace App\Services;

use App\Models\Workshop;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    private function getApiKey(): string
    {
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            throw new \RuntimeException('API Key belum dikonfigurasi.');
        }

        return $apiKey;
    }

    private function buildSystemContext(Workshop $workshop): string
    {
        $lowStockItems = $workshop->spareparts()
            ->where('stock_quantity', '<=', 5)
            ->limit(5)
            ->get()
            ->map(fn($item) => "- {$item->sparepart_name} (Sisa: {$item->stock_quantity})")
            ->implode("\n");

        $todayIncome = $workshop->transactions()
            ->whereIn('status_pembayaran', ['lunas', 'completed'])
            ->whereDate('updated_at', Carbon::today())
            ->sum('total_akhir');

        $pendingCount = $workshop->transactions()
            ->whereIn('status_pembayaran', ['pending', 'process', 'antri'])
            ->count();

        $allSpareparts = $workshop->spareparts()
            ->pluck('sparepart_name')
            ->implode(', ') ?: 'Belum ada data sparepart.';

        return "
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
    }

    private function callGemini(string $prompt): string
    {
        $apiKey = $this->getApiKey();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key={$apiKey}", [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf, saya tidak mengerti.';
        }

        Log::error('AI Error: ' . $response->body());
        throw new \RuntimeException('Sistem AI sedang sibuk, coba lagi nanti.');
    }

    private function formatReply(string $text): string
    {
        $text = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $text);
        $text = str_replace('* ', '<br>• ', $text);
        return nl2br($text);
    }

    public function sendMessage(Workshop $workshop, string $userMessage): string
    {
        $systemContext = $this->buildSystemContext($workshop);
        $prompt = $systemContext . "\n\nUser bertanya: " . $userMessage;

        $reply = $this->callGemini($prompt);

        return $this->formatReply($reply);
    }
}
