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
        Schema::create('child_subcategories', function (Blueprint $table) {
            $table->id();
            $table->string('title');                        // ✅ Child subcategory title
            $table->string('slug')->unique();              // ✅ SEO-friendly slug
            $table->unsignedBigInteger('category');        // ✅ Parent category ID
            $table->unsignedBigInteger('sub_category');    // ✅ Subcategory ID
            $table->string('image')->nullable();           // ✅ Optional image
            $table->text('description')->nullable();       // ✅ Description text
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_subcategories');
    }
};
