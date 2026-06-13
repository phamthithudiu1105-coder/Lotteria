<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (Schema::hasTable('notifications')) {
    if (!Schema::hasColumn('notifications', 'MaPhieuXuat')) {
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('MaPhieuXuat')->nullable();
        });
        echo "MaPhieuXuat column added to notifications table successfully!\n";
    } else {
        echo "MaPhieuXuat column already exists in notifications table.\n";
    }
} else {
    echo "Notifications table doesn't exist!\n";
}