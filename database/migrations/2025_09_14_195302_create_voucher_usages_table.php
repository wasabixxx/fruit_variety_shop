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
        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voucher_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id');
            
            // Discount applied details
            $table->decimal('order_amount', 10, 2); // original order amount
            $table->decimal('discount_amount', 10, 2); // actual discount applied
            $table->string('voucher_code'); // store code for history
            
            // Timestamps
            $table->timestamp('used_at')->useCurrent();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
            // Indexes
            $table->index(['voucher_id', 'user_id']);
            $table->index('used_at');
            $table->unique(['voucher_id', 'order_id']); // prevent duplicate usage on same order
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_usages');
    }
};
