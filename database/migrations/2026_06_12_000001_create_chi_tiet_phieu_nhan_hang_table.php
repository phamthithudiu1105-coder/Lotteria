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
        Schema::create('ChiTietPhieuNhanHang', function (Blueprint $table) {
            $table->string('MaPhieuNhan', 10);
            $table->string('MaDonDatHang', 10);
            $table->string('MaNguyenLieu', 10);
            $table->integer('LanNhan')->default(1); // Lần 1, 2, ...
            $table->integer('SoLuongDat');
            $table->integer('SoLuongThucNhan');
            $table->integer('SoLuongLoi');
            $table->integer('SoLuongTot'); // Thực nhận - Lỗi
            $table->integer('SoLuongThua')->default(0); // Tốt - Đặt (nếu >0)
            $table->integer('SoLuongNhapKho'); // Tốt - Thừa (tối đa bằng Đặt)
            $table->string('GhiChu')->nullable();

            $table->primary(['MaPhieuNhan', 'MaNguyenLieu']);

            $table->foreign('MaPhieuNhan')
                ->references('MaPhieuNhan')
                ->on('PhieuNhanHang');

            $table->foreign('MaNguyenLieu')
                ->references('MaNguyenLieu')
                ->on('NguyenLieu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ChiTietPhieuNhanHang');
    }
};
