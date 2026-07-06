<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __construct(
        private ChatbotService $chatbotService
    ) {}

    public function sendMessage(Request $request)
    {
        Log::info("===== CHATBOT REQUEST MASUK =====");
        Log::info("User input:", ['message' => $request->message]);

        try {
            $request->validate(['message' => 'required|string']);
        } catch (\Exception $e) {
            Log::error("VALIDATION ERROR: " . $e->getMessage());
            return response()->json(['reply' => 'Input tidak valid.'], 422);
        }

        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        try {
            $reply = $this->chatbotService->sendMessage($workshop, $request->message);

            return response()->json(['reply' => $reply]);
        } catch (\RuntimeException $e) {
            $statusCode = str_contains($e->getMessage(), 'API Key') ? 500 : 503;
            return response()->json(['reply' => $e->getMessage()], $statusCode);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['reply' => 'Terjadi kesalahan jaringan.'], 500);
        }
    }
}
