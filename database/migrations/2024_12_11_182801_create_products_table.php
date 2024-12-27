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
            $table->id(); // 1
            $table->string('name'); // Product name
            $table->string('video')->nullable(); // String column for a single video path
            $table->text('description'); // Product description
            $table->string('seller_name'); // Seller name
            $table->string('email'); // Seller email
            $table->string('instagram')->nullable(); // Instagram link (nullable)
            $table->string('linkedin')->nullable(); // LinkedIn link (nullable)
            $table->string('github')->nullable(); // GitHub link (nullable)
            $table->string('product_link'); // Product link (required)
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
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
