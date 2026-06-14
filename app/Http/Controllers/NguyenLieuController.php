<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NguyenLieu;
use PhpOffice\PhpSpreadsheet\IOFactory;

class NguyenLieuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy toàn bộ dữ liệu từ bảng nguyen_lieus
        $danhSachNL = NguyenLieu::all();

        // Truyền dữ liệu đó sang file giao diện
        return view('NguyenLieu.index', compact('danhSachNL'));
    }

    public function uploadExcel(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls|max:10240'
            ]);

            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Bỏ qua dòng tiêu đề đầu tiên
            array_shift($rows);

            $countInsert = 0;
            $countUpdate = 0;

            foreach ($rows as $row) {
                if (empty($row[0])) {
                    continue; // Bỏ qua hàng nếu không có Mã NL
                }

                $maNguyenLieu = trim($row[0]);
                $tenNguyenLieu = trim($row[1] ?? '');
                $donViTinh = substr(trim($row[2] ?? ''), 0, 20); // Giới hạn độ dài 20 ký tự
                $nhomHang = substr(trim($row[3] ?? ''), 0, 50); // Giới hạn độ dài 50 ký tự
                $soLuongTonKho = intval($row[4] ?? 0);
                $moTa = substr(trim($row[5] ?? null), 0, 255); // Giới hạn độ dài 255 ký tự

                // Tìm kiếm nguyên liệu theo Mã
                $NguyenLieu = NguyenLieu::where('MaNguyenLieu', $maNguyenLieu)->first();

                if ($NguyenLieu) {
                    // Cập nhật nếu đã tồn tại - không thay đổi SoLuongTonKho
                    $NguyenLieu->update([
                        'TenNguyenLieu' => $tenNguyenLieu ?: $NguyenLieu->TenNguyenLieu,
                        'NhomHang' => $nhomHang ?: $NguyenLieu->NhomHang,
                        'DonViTinh' => $donViTinh ?: $NguyenLieu->DonViTinh,
                        'MoTa' => $moTa,
                    ]);
                    $countUpdate++;
                } else {
                    // Thêm mới nếu chưa có - SoLuongTonKho mặc định = 0
                    NguyenLieu::create([
                        'MaNguyenLieu' => $maNguyenLieu,
                        'TenNguyenLieu' => $tenNguyenLieu,
                        'NhomHang' => $nhomHang,
                        'SoLuongTonKho' => 0,
                        'DonViTinh' => $donViTinh,
                        'MoTa' => $moTa,
                    ]);
                    $countInsert++;
                }
            }

            return redirect('/nguyen-lieu')->with('success', "Đã xử lý thành công! Thêm mới {$countInsert} phần tử, cập nhật {$countUpdate} phần tử.");

        } catch (\Exception $e) {
            return redirect('/nguyen-lieu')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('NguyenLieu.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'MaNguyenLieu' => ['required', 'string', 'max:10', 'unique:NguyenLieu,MaNguyenLieu'],
            'TenNguyenLieu' => ['required', 'string', 'max:100'],
            'DonViTinh' => ['required', 'string', 'max:20'],
            'NhomHang' => ['required', 'string', 'max:50'],
            'MoTa' => ['nullable', 'string', 'max:255'],
        ], [
            'MaNguyenLieu.unique' => 'Không được trùng mã nguyên liệu.',
        ]);

        \App\Models\NguyenLieu::create([
            'MaNguyenLieu' => $validated['MaNguyenLieu'],
            'TenNguyenLieu' => $validated['TenNguyenLieu'],
            'DonViTinh' => $validated['DonViTinh'],
            'NhomHang' => $validated['NhomHang'],
            'SoLuongTonKho' => 0,
            'MoTa' => $validated['MoTa'] ?? null,
        ]);

        // Lưu xong thì quay tự động quay trở lại trang danh sách
        return redirect('/nguyen-lieu')->with('success', 'Đã thêm nguyên liệu mới thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }


    // Hàm mở form Sửa nguyên liệu
    public function edit($id)
    {
        $nl = \App\Models\NguyenLieu::findOrFail($id);
        return view('NguyenLieu.edit', compact('nl'));
    }

    // Hàm nhận dữ liệu từ form Sửa và cập nhật vào Database
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $nl = \App\Models\NguyenLieu::findOrFail($id);
        $nl->update([
            'TenNguyenLieu' => $request->input('TenNguyenLieu'),
            'DonViTinh' => $request->input('DonViTinh'),
            'NhomHang' => $request->input('NhomHang'),
            'MoTa' => $request->input('MoTa'),
        ]);
        return redirect('/nguyen-lieu');
    }

    // Hàm Xóa nguyên liệu
    public function destroy($id)
    {
        try {
            $nl = \App\Models\NguyenLieu::findOrFail($id);
            $nl->delete();
            return redirect('/nguyen-lieu')->with('success', 'Đã xóa nguyên liệu thành công.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Kiểm tra lỗi vi phạm ràng buộc khóa ngoại (Integrity constraint violation)
            if ($e->getCode() == "23000") {
                return redirect()->back()->with('error', 'Không thể xóa do nguyên liệu đang được sử dụng trong hệ thống.');
            }
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa: ' . $e->getMessage());
        }
    }
}
