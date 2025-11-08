<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleXendit(Request $request)
    {
        $callbackToken = $request->header('x-callback-token');

        if ($callbackToken !== config('services.xendit.callback_token')) {
            Log::warning('Invalid Xendit callback token', [
                'provided' => $callbackToken,
            ]);

            return response()->json(['message' => 'Invalid callback token'], 401);
        }

        $event = $request->all();

        $externalId = $event['external_id'] ?? null;
        $status     = $event['status'] ?? null;

        if (!$externalId) {
            return response()->json(['message' => 'No external_id'], 400);
        }

        $order = Order::where('external_id', $externalId)->first();

        if (!$order) {
            Log::warning('Order not found for external_id', ['external_id' => $externalId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($status === 'PAID' || $status === 'SETTLED') {
            $order->update([
                'status'  => 'PAID',
                'paid_at' => now(),
            ]);
        } elseif ($status === 'EXPIRED') {
            $order->update([
                'status' => 'EXPIRED',
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
