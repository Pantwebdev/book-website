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
        Schema::create('sizecolors', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // ✅ Child subcategory title
            $table->string('color_id')->nullable();              // ✅ SEO-friendly slug
            $table->unsignedBigInteger('type');        // ✅ Parent category ID

            $table->string('image')->nullable();           // ✅ Optional image

            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizecolors');
    }
};
