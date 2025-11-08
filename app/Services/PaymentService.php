<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class PaymentService
{
    private InvoiceApi $invoiceApi;

    public function __construct()
    {
        Configuration::setXenditKey(config('services.xendit.secret_key'));
        $this->invoiceApi = new InvoiceApi();
    }

    public function createInvoiceForOrder(Order $order): Order
    {
        $externalId = 'order-' . $order->id . '-' . Str::random(8);

        $payload = new CreateInvoiceRequest([
            'external_id' => $externalId,
            'amount'      => $order->total_amount,
            'description' => 'Pembayaran order #' . $order->id,
        ]);

        $invoice = $this->invoiceApi->createInvoice($payload);

        $order->update([
            'external_id' => $externalId,
            'invoice_id'  => $invoice['id'] ?? null,
            'invoice_url' => $invoice['invoice_url'] ?? null,
            'status'      => 'WAITING_PAYMENT',
            'payment_method'=> 'XENDIT_INVOICE',
        ]);

        return $order->fresh();
    }
}
