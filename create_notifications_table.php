<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasTable('notifications')) {
    Schema::create('notifications', function (Blueprint $table) {
        $table->id();
        $table->string('MaTaiKhoan');
        $table->string('type');
        $table->string('title');
        $table->text('message');
        $table->string('MaPhieuKiemKe')->nullable();
        $table->text('data')->nullable();
        $table->boolean('is_read')->default(false);
        $table->timestamps();
    });
    echo "Notifications table created successfully!\n";
} else {
    echo "Notifications table already exists.\n";
}
