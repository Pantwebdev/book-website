<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('master_orders', function (Blueprint $table) {

            // COD Specific Status
            $table->enum('cod_payment_status', [
                'pending', 'collected', 'failed', 'refunded',
            ])->default('pending')->after('payment_status');

            $table->enum('cod_order_status', [
                'order_placed', 'confirmed', 'processing',
                'shipped', 'delivered', 'cancelled',
            ])->default('order_placed')->after('order_status');

            // Online Payment Specific Status
            $table->enum('online_payment_status', [
                'pending', 'initiated', 'successful',
                'failed', 'pending_verification', 'refunded',
            ])->default('pending')->after('cod_payment_status');

            $table->enum('online_order_status', [
                'order_placed', 'payment_initiated',
                'payment_successful', 'payment_failed',
                'payment_verified', 'confirmed',
                'processing', 'shipped',
                'delivered', 'cancelled',
            ])->default('order_placed')->after('cod_order_status');

            // Common timestamps
            $table->timestamp('payment_initiated_at')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->timestamp('payment_collected_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            // Status history
            $table->json('status_history')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('master_orders', function (Blueprint $table) {
            $table->dropColumn([
                'cod_payment_status',
                'cod_order_status',
                'online_payment_status',
                'online_order_status',
                'payment_initiated_at',
                'payment_verified_at',
                'payment_collected_at',
                'refunded_at',
                'status_history',
            ]);
        });
    }
};
