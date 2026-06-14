<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

abstract class Controller
{
    /**
     * Cập nhật SoLuongTonKho của nguyên liệu bằng tổng SoLuongConLai của tất cả các lô của nguyên liệu đó
     * 
     * @param string $maNguyenLieu
     * @return void
     */
    protected function updateIngredientStock($maNguyenLieu)
    {
        $tongTonKho = DB::table('LoHang')
            ->where('MaNguyenLieu', $maNguyenLieu)
            ->sum('SoLuongConLai');

        DB::table('NguyenLieu')
            ->where('MaNguyenLieu', $maNguyenLieu)
            ->update(['SoLuongTonKho' => $tongTonKho]);

        // Kiểm tra nếu tồn kho dưới 50, gửi thông báo cho quản lý
        if ($tongTonKho < 50) {
            $this->sendLowStockNotification($maNguyenLieu, $tongTonKho);
        }
    }

    /**
     * Gửi thông báo tồn kho thấp cho quản lý
     * 
     * @param string $maNguyenLieu
     * @param int $tongTonKho
     * @return void
     */
    private function sendLowStockNotification($maNguyenLieu, $tongTonKho)
    {
        $nguyenLieu = DB::table('NguyenLieu')
            ->where('MaNguyenLieu', $maNguyenLieu)
            ->first();

        if (!$nguyenLieu) {
            return;
        }

        $accountTable = $this->resolveExistingTable(['TaiKhoan', 'taikhoan']);
        if (!$accountTable) {
            return;
        }

        $managers = DB::table($accountTable)
            ->whereIn('VaiTro', ['Quản lý', 'Quan ly'])
            ->get();

        foreach ($managers as $manager) {
            // Kiểm tra xem đã có thông báo chưa đọc cho nguyên liệu này chưa
            $existingNotification = DB::table('notifications')
                ->where('MaTaiKhoan', $manager->MaTaiKhoan)
                ->where('type', 'low_stock')
                ->where('data->MaNguyenLieu', $maNguyenLieu)
                ->where('is_read', false)
                ->first();

            if (!$existingNotification) {
                DB::table('notifications')->insert([
                    'MaTaiKhoan' => $manager->MaTaiKhoan,
                    'type' => 'low_stock',
                    'title' => 'Tồn kho thấp',
                    'message' => "Nguyên liệu {$nguyenLieu->TenNguyenLieu} ({$maNguyenLieu}) có tồn kho {$tongTonKho} dưới 50, hãy tạo đơn đặt hàng!",
                    'data' => json_encode(['MaNguyenLieu' => $maNguyenLieu]),
                    'is_read' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

    private function resolveExistingTable(array $candidates): ?string
    {
        foreach ($candidates as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }
        return null;
    }
}
