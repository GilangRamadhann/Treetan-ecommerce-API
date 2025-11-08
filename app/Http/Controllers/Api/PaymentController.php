<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function pay(Order $order, PaymentService $paymentService)
    {
        // pastikan order milik user yg login
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Forbidden');
        }

        if ($order->status === 'PAID') {
            return response()->json([
                'message' => 'Order sudah dibayar',
                'order'   => $order,
            ]);
        }

        $order = $paymentService->createInvoiceForOrder($order);

        return response()->json([
            'order' => $order->load('items.product'),
        ]);
    }
}
