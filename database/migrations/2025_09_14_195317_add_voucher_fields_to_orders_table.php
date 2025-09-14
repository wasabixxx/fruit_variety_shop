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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('voucher_id')->nullable()->after('total_amount');
            $table->string('voucher_code')->nullable()->after('voucher_id');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('voucher_code');
            $table->decimal('subtotal', 10, 2)->nullable()->after('discount_amount'); // amount before discount
            
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('set null');
            $table->index('voucher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['voucher_id', 'voucher_code', 'discount_amount', 'subtotal']);
        });
    }
};
