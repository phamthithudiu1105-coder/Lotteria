<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo 'Bắt đầu tạo/cập nhật bảng...' . PHP_EOL;

// Xóa bảng cũ nếu có
try {
    DB::statement('DROP TABLE IF EXISTS chitietxulydondathang');
    echo 'Đã xóa bảng chitietxulydondathang cũ' . PHP_EOL;
} catch (Exception $e) {
    echo 'Không thể xóa bảng chitietxulydondathang: ' . $e->getMessage() . PHP_EOL;
}

try {
    DB::statement('DROP TABLE IF EXISTS ChiTietPhieuNhanHang');
    echo 'Đã xóa bảng ChiTietPhieuNhanHang cũ' . PHP_EOL;
} catch (Exception $e) {
    echo 'Không thể xóa bảng ChiTietPhieuNhanHang: ' . $e->getMessage() . PHP_EOL;
}

// Tạo bảng ChiTietPhieuNhanHang
try {
    DB::statement('
        CREATE TABLE ChiTietPhieuNhanHang (
            MaPhieuNhan VARCHAR(10) NOT NULL,
            MaDonDatHang VARCHAR(10) NOT NULL,
            MaNguyenLieu VARCHAR(10) NOT NULL,
            LanNhan INT NOT NULL DEFAULT 1,
            SoLuongDat INT NOT NULL,
            SoLuongThucNhan INT NOT NULL,
            SoLuongLoi INT NOT NULL,
            SoLuongTot INT NOT NULL,
            SoLuongThua INT NOT NULL DEFAULT 0,
            SoLuongNhapKho INT NOT NULL,
            GhiChu VARCHAR(255) NULL,
            PRIMARY KEY (MaPhieuNhan, MaNguyenLieu)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ');
    echo 'Đã tạo bảng ChiTietPhieuNhanHang' . PHP_EOL;
} catch (Exception $e) {
    echo 'Lỗi tạo bảng ChiTietPhieuNhanHang: ' . $e->getMessage() . PHP_EOL;
}

// Tạo bảng chitietxulydondathang
try {
    DB::statement('
        CREATE TABLE chitietxulydondathang (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            MaDonDatHang VARCHAR(10) NOT NULL,
            MaNguyenLieu VARCHAR(10) NOT NULL,
            LanNhan INT NOT NULL DEFAULT 1,
            LoaiXuLyThieu VARCHAR(255) NULL,
            LoaiXuLyThua VARCHAR(255) NULL,
            LoaiXuLyLoi VARCHAR(255) NULL,
            SoLuongCanGiaoBu INT NOT NULL DEFAULT 0,
            SoLuongCanDoi INT NOT NULL DEFAULT 0,
            GhiChu TEXT NULL,
            MaTaiKhoanXuLy VARCHAR(10) NOT NULL,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ');
    echo 'Đã tạo bảng chitietxulydondathang' . PHP_EOL;
} catch (Exception $e) {
    echo 'Lỗi tạo bảng chitietxulydondathang: ' . $e->getMessage() . PHP_EOL;
}

echo 'Hoàn thành!' . PHP_EOL;
