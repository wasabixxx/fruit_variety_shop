<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->default(5); // 1-5 stars
            $table->text('comment')->nullable();
            $table->boolean('is_verified')->default(false); // Verified purchase
            $table->boolean('is_approved')->default(true); // Admin approval
            $table->timestamps();
            
            // Prevent duplicate reviews for same product by same user
            $table->unique(['user_id', 'product_id', 'order_id']);
            $table->index(['product_id', 'is_approved']);
            $table->index(['user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
