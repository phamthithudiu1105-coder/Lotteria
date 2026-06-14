<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonHangNVController extends Controller
{
    private const STATUS_WAITING_RECEIVE = 'Chờ nhận hàng';
    private const STATUS_RECEIVED = 'Hoàn tất';
    private const STATUS_WAITING_PROCESS = 'Chờ xử lý';
    private const STATUS_PROCESSING = 'Đang xử lý';

    public function index()
    {
        $orders = DB::table('DonDatHang as d')
            ->join('TaiKhoan as t', 't.MaTaiKhoan', '=', 'd.MaTaiKhoan')
            ->leftJoin('ChiTietDonDatHang as c', 'c.MaDonDatHang', '=', 'd.MaDonDatHang')
            ->select(
                'd.MaDonDatHang',
                'd.NgayDat',
                'd.TrangThai',
                'd.GhiChu',
                't.HoTen',
                DB::raw('COUNT(c.MaNguyenLieu) as SoMatHang'),
                DB::raw('COALESCE(SUM(c.SoLuongDat), 0) as TongSoLuong')
            )
            ->whereIn('d.TrangThai', $this->receivableStatuses())
            ->groupBy('d.MaDonDatHang', 'd.NgayDat', 'd.TrangThai', 'd.GhiChu', 't.HoTen')
            ->orderByDesc('d.NgayDat')
            ->paginate(10);

        return view('nhanvien.ds-don-hang', compact('orders'));
    }

    public function show($order)
    {
        $orderData = DB::table('DonDatHang as d')
            ->join('TaiKhoan as t', 't.MaTaiKhoan', '=', 'd.MaTaiKhoan')
            ->select('d.*', 't.HoTen')
            ->where('d.MaDonDatHang', $order)
            ->first();

        abort_if(!$orderData, 404);

        // Lấy thông tin lần nhận trước nếu có
        $previousReceipts = DB::table('PhieuNhanHang')
            ->where('MaDonDatHang', $order)
            ->orderBy('NgayNhan', 'desc')
            ->get();

        $isSecondReceipt = $previousReceipts->count() > 0;

        // Lấy thông tin cần xử lý từ bảng chitietxulydondathang
        $processingItems = collect();
        if (DB::getSchemaBuilder()->hasTable('chitietxulydondathang')) {
            $processingItems = DB::table('chitietxulydondathang')
                ->where('MaDonDatHang', $order)
                ->get()
                ->keyBy('MaNguyenLieu');
        }

        // Tách items thành giao bù và đổi trả
        $giaoBuItems = collect();
        $doiTraItems = collect();

        $allItems = DB::table('ChiTietDonDatHang as c')
            ->join('NguyenLieu as n', 'n.MaNguyenLieu', '=', 'c.MaNguyenLieu')
            ->select('c.*', 'n.TenNguyenLieu', 'n.DonViTinh')
            ->where('c.MaDonDatHang', $order)
            ->get()
            ->map(function ($item) use ($processingItems, $isSecondReceipt) {
                if ($isSecondReceipt && $processingItems->has($item->MaNguyenLieu)) {
                    $procItem = $processingItems[$item->MaNguyenLieu];
                    $item->SoLuongCanGiaoBu = $procItem->SoLuongCanGiaoBu;
                    $item->SoLuongCanDoi = $procItem->SoLuongCanDoi;
                    $item->LoaiXuLyThieu = $procItem->LoaiXuLyThieu;
                    $item->LoaiXuLyThua = $procItem->LoaiXuLyThua;
                    $item->LoaiXuLyLoi = $procItem->LoaiXuLyLoi;
                    $item->SoLuongCanNhan = $procItem->SoLuongCanGiaoBu + $procItem->SoLuongCanDoi;
                } else {
                    $item->SoLuongCanGiaoBu = 0;
                    $item->SoLuongCanDoi = 0;
                    $item->LoaiXuLyThieu = null;
                    $item->LoaiXuLyThua = null;
                    $item->LoaiXuLyLoi = null;
                    $item->SoLuongCanNhan = $item->SoLuongDat;
                }
                return $item;
            });

        foreach ($allItems as $item) {
            if ($item->LoaiXuLyThieu === 'giao_bu' && $item->SoLuongCanGiaoBu > 0) {
                $giaoBuItems->push($item);
            }
            if ($item->SoLuongCanDoi > 0) {
                $doiTraItems->push($item);
            }
        }

        // Lấy thông tin chi tiết các lần nhận trước để hiển thị
        $previousReceiptDetails = collect();
        if ($isSecondReceipt && DB::getSchemaBuilder()->hasTable('ChiTietPhieuNhanHang')) {
            $previousReceiptDetails = DB::table('ChiTietPhieuNhanHang')
                ->where('MaDonDatHang', $order)
                ->where('LanNhan', 1)
                ->get()
                ->keyBy('MaNguyenLieu');
        }

        return view('nhanvien.tao-phieu-nhan-hang', compact('orderData', 'giaoBuItems', 'doiTraItems', 'isSecondReceipt', 'previousReceiptDetails', 'processingItems'));
    }

    public function store(Request $request, $order)
    {
        // Validate based on receipt type
        $soLanNhan = DB::table('PhieuNhanHang')->where('MaDonDatHang', $order)->count() + 1;
        $validationRules = [
            'NgayNhan' => 'required|date',
            'GhiChu' => 'nullable|string|max:255',
        ];

        if ($soLanNhan === 1) {
            $validationRules['items'] = 'required|array|min:1';
            $validationRules['items.*.SoLuongThucNhan'] = 'required|integer|min:0';
            $validationRules['items.*.SoLuongLoi'] = 'required|integer|min:0|lte:items.*.SoLuongThucNhan';
            $validationRules['items.*.NgaySanXuat'] = 'required|date|before_or_equal:today';
            $validationRules['items.*.HanSuDung'] = 'required|date|after:items.*.NgaySanXuat';
        } else {
            $validationRules['giaoBuItems'] = 'nullable|array';
            $validationRules['giaoBuItems.*.SoLuongThucNhan'] = 'required|integer|min:0';
            $validationRules['giaoBuItems.*.SoLuongLoi'] = 'required|integer|min:0|lte:giaoBuItems.*.SoLuongThucNhan';
            $validationRules['giaoBuItems.*.NgaySanXuat'] = 'required|date|before_or_equal:today';
            $validationRules['giaoBuItems.*.HanSuDung'] = 'required|date|after:giaoBuItems.*.NgaySanXuat';

            $validationRules['doiTraItems'] = 'nullable|array';
            $validationRules['doiTraItems.*.SoLuongThucNhan'] = 'required|integer|min:0';
            $validationRules['doiTraItems.*.SoLuongLoi'] = 'required|integer|min:0|lte:doiTraItems.*.SoLuongThucNhan';
            $validationRules['doiTraItems.*.NgaySanXuat'] = 'required|date|before_or_equal:today';
            $validationRules['doiTraItems.*.HanSuDung'] = 'required|date|after:doiTraItems.*.NgaySanXuat';
        }

        $request->validate($validationRules, [
            'items.*.NgaySanXuat.before_or_equal' => 'Ngày sản xuất không được lớn hơn ngày hiện tại.',
            'items.*.HanSuDung.after' => 'Hạn sử dụng phải lớn hơn Ngày sản xuất.',
            'items.*.SoLuongLoi.lte' => 'Số lượng lỗi không được lớn hơn số lượng thực nhận.',
            'giaoBuItems.*.NgaySanXuat.before_or_equal' => 'Ngày sản xuất không được lớn hơn ngày hiện tại.',
            'giaoBuItems.*.HanSuDung.after' => 'Hạn sử dụng phải lớn hơn Ngày sản xuất.',
            'giaoBuItems.*.SoLuongLoi.lte' => 'Số lượng lỗi không được lớn hơn số lượng thực nhận.',
            'doiTraItems.*.NgaySanXuat.before_or_equal' => 'Ngày sản xuất không được lớn hơn ngày hiện tại.',
            'doiTraItems.*.HanSuDung.after' => 'Hạn sử dụng phải lớn hơn Ngày sản xuất.',
            'doiTraItems.*.SoLuongLoi.lte' => 'Số lượng lỗi không được lớn hơn số lượng thực nhận.',
        ]);

        $currentStatus = DB::table('DonDatHang')->where('MaDonDatHang', $order)->value('TrangThai');
        if (! in_array($currentStatus, $this->receivableStatuses(), true)) {
            return back()->with('error', 'Đơn hàng không ở trạng thái có thể nhận!');
        }

        DB::beginTransaction();
        try {
            // Tạo phiếu nhận hàng
            $lastReceipt = DB::table('PhieuNhanHang')
                ->where('MaPhieuNhan', 'like', 'PN%')
                ->orderByDesc('MaPhieuNhan')
                ->first();
            $nextNumber = $lastReceipt ? ((int) substr($lastReceipt->MaPhieuNhan, 2)) + 1 : 1;
            $maPhieuNhan = 'PN' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            DB::table('PhieuNhanHang')->insert([
                'MaPhieuNhan' => $maPhieuNhan,
                'NgayNhan' => $request->NgayNhan,
                'GhiChu' => $request->GhiChu,
                'MaTaiKhoan' => auth()->user()->MaTaiKhoan,
                'MaDonDatHang' => $order,
            ]);

            $hasDiscrepancy = false;
            $hasExpiredItem = false;
            $maNguyenLieus = [];

            // Lấy thông tin cần nhận cho lần 2 nếu có
            $processingItems = collect();
            if (DB::getSchemaBuilder()->hasTable('chitietxulydondathang')) {
                $processingItems = DB::table('chitietxulydondathang')
                    ->where('MaDonDatHang', $order)
                    ->get()
                    ->keyBy('MaNguyenLieu');
            }

            // Determine which items to process
            $itemsToProcess = [];
            if ($soLanNhan === 1) {
                $itemsToProcess = $request->items;
            } else {
                $itemsToProcess = array_merge(
                    $request->giaoBuItems ?? [],
                    $request->doiTraItems ?? []
                );
            }

            // Lưu thông tin chi tiết phiếu nhận hàng và lô hàng
            foreach ($itemsToProcess as $item) {
                $soLuongDat = DB::table('ChiTietDonDatHang')
                    ->where('MaDonDatHang', $order)
                    ->where('MaNguyenLieu', $item['MaNguyenLieu'])
                    ->value('SoLuongDat');

                $soLuongCanNhan = $soLuongDat;
                if ($soLanNhan > 1 && $processingItems->has($item['MaNguyenLieu'])) {
                    $procItem = $processingItems[$item['MaNguyenLieu']];
                    $soLuongCanNhan = $procItem->SoLuongCanGiaoBu + $procItem->SoLuongCanDoi;
                }

                $soLuongThucNhan = $item['SoLuongThucNhan'];
                $soLuongLoi = $item['SoLuongLoi'];
                $soLuongTot = $soLuongThucNhan - $soLuongLoi;
                $soLuongThua = max(0, $soLuongTot - $soLuongCanNhan);
                $soLuongNhapKho = $soLuongTot - $soLuongThua;

                // Kiểm tra có sai lệch không
                if ($soLuongLoi > 0 || $soLuongThua > 0 || $soLuongTot < $soLuongCanNhan) {
                    $hasDiscrepancy = true;
                }

                // Lưu chi tiết phiếu nhận hàng
                if (DB::getSchemaBuilder()->hasTable('ChiTietPhieuNhanHang')) {
                    DB::table('ChiTietPhieuNhanHang')->insert([
                        'MaPhieuNhan' => $maPhieuNhan,
                        'MaDonDatHang' => $order,
                        'MaNguyenLieu' => $item['MaNguyenLieu'],
                        'LanNhan' => $soLanNhan,
                        'SoLuongDat' => $soLuongCanNhan,
                        'SoLuongThucNhan' => $soLuongThucNhan,
                        'SoLuongLoi' => $soLuongLoi,
                        'SoLuongTot' => $soLuongTot,
                        'SoLuongThua' => $soLuongThua,
                        'SoLuongNhapKho' => $soLuongNhapKho,
                    ]);
                }

                // Chỉ tạo lô hàng và nhập kho cho số lượng tốt
                if ($soLuongNhapKho > 0) {
                    // Tạo mã lô hàng
                    $lastLoHang = DB::table('LoHang')
                        ->where('MaLoHang', 'like', 'LH%')
                        ->orderByDesc('MaLoHang')
                        ->first();
                    $loHangNumber = $lastLoHang ? ((int) substr($lastLoHang->MaLoHang, 2)) + 1 : 1;
                    $maLoHang = 'LH' . str_pad($loHangNumber, 3, '0', STR_PAD_LEFT);

                    // Xác định trạng thái lô hàng
                    $ngayHienTai = now()->startOfDay();
                    $hanSuDung = \Illuminate\Support\Carbon::parse($item['HanSuDung'])->startOfDay();
                    $trangThai = 'Còn hạn';
                    if ($hanSuDung->isBefore($ngayHienTai)) {
                        $trangThai = 'Hết hạn';
                        $hasExpiredItem = true;
                    } elseif ($hanSuDung->diffInDays($ngayHienTai) <= 15) {
                        $trangThai = 'Sắp hết hạn';
                    }

                    DB::table('LoHang')->insert([
                        'MaLoHang' => $maLoHang,
                        'NgaySanXuat' => $item['NgaySanXuat'],
                        'HanSuDung' => $item['HanSuDung'],
                        'SoLuongNhap' => $soLuongNhapKho,
                        'SoLuongConLai' => $soLuongNhapKho,
                        'TrangThai' => $trangThai,
                        'MaNguyenLieu' => $item['MaNguyenLieu'],
                        'MaPhieuNhan' => $maPhieuNhan,
                    ]);

                    // Thêm vào danh sách nguyên liệu cần cập nhật tổng tồn
                    $maNguyenLieus[$item['MaNguyenLieu']] = true;
                }
            }

            // Cập nhật tổng tồn kho cho từng nguyên liệu
            foreach (array_keys($maNguyenLieus) as $maNguyenLieu) {
                $this->updateIngredientStock($maNguyenLieu);
            }

            // Xác định trạng thái mới của đơn hàng
            $trangThaiMoi = ($hasDiscrepancy || $hasExpiredItem) ? self::STATUS_WAITING_PROCESS : self::STATUS_RECEIVED;

            // Lưu lịch sử truy vết (nếu có bảng)
            if (DB::getSchemaBuilder()->hasTable('TruyVetDonDatHang')) {
                DB::table('TruyVetDonDatHang')->insert([
                    'MaDonDatHang' => $order,
                    'HanhDong' => 'Nhận hàng',
                    'TrangThaiTruoc' => $currentStatus,
                    'TrangThaiSau' => $trangThaiMoi,
                    'MaTaiKhoan' => auth()->user()->MaTaiKhoan,
                    'NoiDung' => $request->GhiChu ?? "Nhân viên tạo phiếu nhận hàng lần $soLanNhan" . ($hasExpiredItem ? ' (Có hàng hết hạn)' : ''),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Cập nhật trạng thái đơn hàng
            DB::table('DonDatHang')
                ->where('MaDonDatHang', $order)
                ->update(['TrangThai' => $trangThaiMoi]);

            // Send notification to managers
            $managerAccounts = DB::table('TaiKhoan')
                ->whereIn('VaiTro', ['Quản lý', 'Quan ly'])
                ->get();

            $notificationType = $trangThaiMoi === self::STATUS_WAITING_PROCESS ? 'donhang_waiting' : 'donhang_completed';
            $notificationTitle = $trangThaiMoi === self::STATUS_WAITING_PROCESS ? "$order cần xử lý" : "$order đã hoàn tất và nhập kho thành công";
            $notificationMessage = $trangThaiMoi === self::STATUS_WAITING_PROCESS ? "Đơn hàng $order cần xử lý." : "Đơn hàng $order đã hoàn tất và nhập kho thành công.";

            foreach ($managerAccounts as $manager) {
                DB::table('notifications')->insert([
                    'MaTaiKhoan' => $manager->MaTaiKhoan,
                    'type' => $notificationType,
                    'title' => $notificationTitle,
                    'message' => $notificationMessage,
                    'data' => json_encode(['MaDonDatHang' => $order]),
                    'is_read' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            if ($trangThaiMoi === self::STATUS_RECEIVED) {
                return redirect()->route('ds-don-hang.index')
                    ->with('success', 'Tạo phiếu nhận hàng thành công! Đơn hàng đã chuyển trạng thái Hoàn tất.');
            } else {
                $msg = 'Tạo phiếu nhận hàng thành công! ';
                if ($hasExpiredItem) {
                    $msg .= 'Phát hiện nguyên liệu đã hết hạn sử dụng. ';
                }
                if ($hasDiscrepancy) {
                    $msg .= 'Phát hiện sai lệch số lượng/lỗi hàng. ';
                }
                $msg .= 'Đơn hàng chuyển trạng thái Chờ xử lý.';

                return redirect()->route('ds-don-hang.index')
                    ->with('warning', $msg);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    private function receivableStatuses(): array
    {
        return [
            self::STATUS_WAITING_RECEIVE,
            self::STATUS_PROCESSING,
        ];
    }
}
