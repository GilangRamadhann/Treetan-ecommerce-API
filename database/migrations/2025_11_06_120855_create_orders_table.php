<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('total_amount');
            $table->string('status')->default('PENDING_PAYMENT'); 
            $table->string('payment_method')->nullable();

            // integrasi Xendit
            $table->string('external_id')->nullable()->unique();
            $table->string('invoice_id')->nullable();
            $table->string('invoice_url')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
