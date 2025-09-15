<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Tạo 20 test pages
for($i = 1; $i <= 20; $i++) {
    \App\Models\Page::create([
        'title' => 'Test Page ' . $i,
        'slug' => 'test-page-' . $i,
        'content' => 'This is test page content ' . $i,
        'template' => 'default',
        'is_published' => true,
        'show_in_menu' => false,
        'created_by' => 1,
        'updated_by' => 1
    ]);
}

echo "Created 20 test pages successfully!\n";