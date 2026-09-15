<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // अगर columns पहले से मौजूद नहीं हैं तो उन्हें जोड़ें
            if (!Schema::hasColumn('cart_items', 'status')) {
                $table->enum('status', ['active', 'ordered', 'saved'])->default('active');
            }
            if (!Schema::hasColumn('cart_items', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable();
                $table->foreign('order_id')->references('id')->on('master_orders')->onDelete('set null');
            }
            if (!Schema::hasColumn('cart_items', 'guest_token')) {
                $table->string('guest_token')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('cart_items', 'name')) {
                $table->string('name')->nullable()->after('product_id');
            }
            if (!Schema::hasColumn('cart_items', 'sku')) {
                $table->string('sku')->nullable()->after('name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // columns drop करें
            $table->dropColumn(['status', 'order_id', 'guest_token', 'name', 'sku']);
        });
    }
};
