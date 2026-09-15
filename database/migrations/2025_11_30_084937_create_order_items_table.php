<?php

// database/migrations/2024_01_01_000001_create_order_items_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Product details at time of order (in case product changes later)
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->integer('qty');
            $table->decimal('price', 10, 2); // Selling price
            $table->decimal('mrp_price', 10, 2); // Maximum Retail Price
            $table->decimal('discount', 5, 2)->default(0); // Discount percentage

            // Variants
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->string('image')->nullable();

            // Totals for this item
            $table->decimal('item_total', 10, 2)->default(0);
            $table->decimal('item_mrp_total', 10, 2)->default(0);
            $table->decimal('item_discount', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_item');
    }
}
