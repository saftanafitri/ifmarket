<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Product name
            $table->json('photos')->nullable(); // JSON column for multiple photo paths
            $table->string('video')->nullable(); // String column for a single video path
            $table->string('category'); // Product category
            $table->text('description'); // Product description
            $table->string('seller_name'); // Seller name
            $table->string('email'); // Seller email
            $table->string('instagram')->nullable(); // Instagram link (nullable)
            $table->string('linkedin')->nullable(); // LinkedIn link (nullable)
            $table->string('github')->nullable(); // GitHub link (nullable)
            $table->string('product_link'); // Product link (required)
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
