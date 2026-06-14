<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

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
        $tongTonKho = DB::table('lohang')
            ->where('MaNguyenLieu', $maNguyenLieu)
            ->sum('SoLuongConLai');

        DB::table('nguyenlieu')
            ->where('MaNguyenLieu', $maNguyenLieu)
            ->update(['SoLuongTonKho' => $tongTonKho]);
    }
}
