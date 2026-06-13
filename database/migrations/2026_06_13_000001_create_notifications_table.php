<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('MaTaiKhoan'); // Recipient
            $table->string('type'); // e.g., 'kiemke_approved', 'kiemke_rejected'
            $table->string('title');
            $table->text('message');
            $table->string('MaPhieuKiemKe')->nullable();
            $table->text('data')->nullable(); // JSON data for extra info
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};
