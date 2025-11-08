<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request, PaymentService $paymentService)
    {
        $data = $request->validate([
            'items'   => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($data, $user, $paymentService) {
            $total = 0;
            $itemsPayload = [];

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    abort(422, "Stock produk {$product->name} tidak mencukupi");
                }

                $lineTotal = $product->price * $item['quantity'];
                $total += $lineTotal;

                $itemsPayload[] = [
                    'product'  => $product,
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal,
                ];

                // kurangi stok
                $product->decrement('stock', $item['quantity']);
            }

            $order = Order::create([
                'user_id'      => $user->id,
                'total_amount' => $total,
                'status'       => 'PENDING_PAYMENT',
            ]);

            foreach ($itemsPayload as $row) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $row['product']->id,
                    'quantity'   => $row['quantity'],
                    'price'      => $row['product']->price,
                ]);
            }

            // langsung create invoice (bisa juga dipisah ke Payment API)
            $order = $paymentService->createInvoiceForOrder($order);

            return response()->json([
                'order' => $order->load('items.product'),
            ], 201);
        });
    }
}
