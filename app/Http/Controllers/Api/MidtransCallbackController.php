<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function __construct(
        private MidtransService $midtransService
    ) {}

    public function handle(Request $request)
    {
        try {
            $result = $this->midtransService->processNotification();

            return response()->json($result);
        } catch (\RuntimeException $e) {
            $statusCode = match ($e->getMessage()) {
                'Invalid Order ID format' => 400,
                'Invalid Subscription ID' => 400,
                'Subscription not found' => 404,
                default => 500,
            };

            return response()->json(['message' => $e->getMessage()], $statusCode);
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());

            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }
}
