<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Tạo order mẫu
$order = App\Models\Order::create([
    'customer_name' => 'Test Customer',
    'customer_phone' => '0123456789',
    'customer_address' => '123 Test Street, Test City',
    'total_amount' => 50000,
    'payment_method' => 'cod',
    'payment_status' => 'pending',
    'order_status' => 'pending'
]);

echo "Order created with ID: " . $order->id . "\n";
