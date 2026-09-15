<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->unsignedBigInteger('child_sub_category_id')->nullable();

            $table->string('sku')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tag')->nullable();
            $table->decimal('display_price', 10, 2);
            $table->decimal('mrp_price', 10, 2)->nullable();
            $table->integer('stock');

            $table->json('colors')->nullable();
            $table->json('sizes')->nullable();

            $table->boolean('product_on_sale')->default(0);
            $table->boolean('new_arrivals')->default(0);
            $table->boolean('bulk_products')->default(0);

            $table->json('bulk_price_batches')->nullable();
            $table->json('features')->nullable();
            $table->longText('specification')->nullable();
            $table->longText('description')->nullable();

            $table->string('image')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('image5')->nullable();
            $table->json('multipleimage')->nullable();

            $table->string('meta_slug')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->longText('meta_description')->nullable();

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
