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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Voucher code (e.g., SAVE20, WELCOME10)
            $table->string('name'); // Display name for admin
            $table->text('description')->nullable(); // Description of the voucher
            
            // Discount type and amount
            $table->enum('type', ['percentage', 'fixed']); // percentage or fixed amount
            $table->decimal('amount', 10, 2); // discount amount or percentage
            
            // Usage and limits
            $table->integer('usage_limit')->nullable(); // max total uses (null = unlimited)
            $table->integer('usage_limit_per_user')->default(1); // max uses per user
            $table->integer('used_count')->default(0); // how many times it's been used
            
            // Minimum order requirements
            $table->decimal('minimum_order_amount', 10, 2)->nullable(); // minimum order value
            
            // Date constraints
            $table->datetime('starts_at')->nullable(); // when voucher becomes active
            $table->datetime('expires_at')->nullable(); // when voucher expires
            
            // Status and visibility
            $table->boolean('is_active')->default(true); // can be disabled by admin
            $table->boolean('is_public')->default(true); // public or targeted voucher
            
            // Target specific users/categories
            $table->json('applicable_categories')->nullable(); // category IDs (null = all)
            $table->json('applicable_users')->nullable(); // user IDs (null = all users)
            
            // Admin tracking
            $table->unsignedBigInteger('created_by'); // admin who created it
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['code', 'is_active']);
            $table->index(['expires_at', 'is_active']);
            $table->index('starts_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
