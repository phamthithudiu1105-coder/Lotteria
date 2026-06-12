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
        Schema::create('chitietxulydondathang', function (Blueprint $table) {
            $table->id();
            $table->string('MaDonDatHang', 10);
            $table->string('MaNguyenLieu', 10);
            $table->integer('LanNhan')->default(1);
            $table->string('LoaiXuLyThieu')->nullable(); // 'giao_bu' or 'huy'
            $table->string('LoaiXuLyThua')->nullable(); // 'nhap_toan_bo' or 'tra'
            $table->string('LoaiXuLyLoi')->nullable(); // 'tra' or 'doi'
            $table->integer('SoLuongCanGiaoBu')->default(0);
            $table->integer('SoLuongCanDoi')->default(0);
            $table->text('GhiChu')->nullable();
            $table->string('MaTaiKhoanXuLy', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chitietxulydondathang');
    }
};
