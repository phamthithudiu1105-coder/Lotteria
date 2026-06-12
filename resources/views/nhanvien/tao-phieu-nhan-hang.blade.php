@extends('layouts.app')

@section('title', 'Tạo phiếu nhận hàng - ' . $orderData->MaDonDatHang)

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="text-lotteria fw-bold mb-1">
            @if ($isSecondReceipt)
                Nhận hàng bổ sung - {{ $orderData->MaDonDatHang }}
            @else
                Tạo phiếu nhận hàng - {{ $orderData->MaDonDatHang }}
            @endif
        </h2>
        <p class="text-muted mb-0">Ngày đặt: {{ \Illuminate\Support\Carbon::parse($orderData->NgayDat)->format('d/m/Y') }} - Người tạo: {{ $orderData->HoTen }}</p>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('ds-don-hang.index') }}">Quay lại</a>
</div>

<form method="post" action="{{ route('ds-don-hang.store', $orderData->MaDonDatHang) }}">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card page-card">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-1 fw-bold">Thông tin phiếu nhận hàng</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="NgayNhan" class="form-label fw-semibold">Ngày nhận</label>
                            <input id="NgayNhan" name="NgayNhan" type="date" class="form-control bg-light" value="{{ old('NgayNhan') }}" data-default-today="true" readonly required style="pointer-events: none;" tabindex="-1">
                        </div>
                        <div class="col-md-8">
                            <label for="GhiChu" class="form-label fw-semibold">Ghi chú</label>
                            <input id="GhiChu" name="GhiChu" maxlength="255" class="form-control" placeholder="Nhập ghi chú nếu có...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($isSecondReceipt)
            <div class="col-lg-12 mb-4">
                <div class="card page-card">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold">Thông tin nhận hàng lần trước</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="col-2">Mã nguyên liệu</th>
                                        <th class="col-2">Tên nguyên liệu</th>
                                        <th class="col-1">Số lượng đặt</th>
                                        <th class="col-1">Đơn vị</th>
                                        <th class="col-1">Thực nhận lần 1</th>
                                        <th class="col-1">Lỗi lần 1</th>
                                        <th class="col-1">Trả hàng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (DB::table('ChiTietDonDatHang as c')->join('NguyenLieu as n', 'n.MaNguyenLieu', '=', 'c.MaNguyenLieu')->select('c.*', 'n.TenNguyenLieu', 'n.DonViTinh')->where('c.MaDonDatHang', $orderData->MaDonDatHang)->get() as $index => $item)
                                        @php
                                            $prevItem = $previousReceiptDetails->get($item->MaNguyenLieu);
                                            $procItem = $processingItems->get($item->MaNguyenLieu);
                                            $soLuongTraHang = 0;
                                            if ($procItem && $prevItem) {
                                                if ($procItem->LoaiXuLyThua === 'tra') {
                                                    $soLuongTraHang += ($prevItem->SoLuongThua ?? 0);
                                                }
                                                if ($procItem->LoaiXuLyLoi === 'tra' || $procItem->LoaiXuLyLoi === 'doi') {
                                                    $soLuongTraHang += ($prevItem->SoLuongLoi ?? 0);
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="fw-bold">{{ $item->MaNguyenLieu }}</td>
                                            <td>{{ $item->TenNguyenLieu }}</td>
                                            <td>{{ $item->SoLuongDat }}</td>
                                            <td>{{ $item->DonViTinh }}</td>
                                            <td>
                                                <input type="text" class="form-control bg-light" value="{{ $prevItem->SoLuongThucNhan ?? 0 }}" readonly style="pointer-events: none;">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control bg-light" value="{{ $prevItem->SoLuongLoi ?? 0 }}" readonly style="pointer-events: none;">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control bg-light text-danger" value="{{ $soLuongTraHang }}" readonly style="pointer-events: none;">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if ($giaoBuItems->count() > 0)
                <div class="col-lg-12 mb-4">
                    <div class="card page-card">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="mb-1 fw-bold">Chi tiết nguyên liệu nhận hàng (Giao bù)</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="col-2">Mã nguyên liệu</th>
                                            <th class="col-2">Tên nguyên liệu</th>
                                            <th class="col-1">Cần giao bù</th>
                                            <th class="col-1">Đơn vị</th>
                                            <th class="col-1">Số lượng thực nhận</th>
                                            <th class="col-1">Số lượng lỗi</th>
                                            <th class="col-2">Ngày sản xuất</th>
                                            <th class="col-2">Hạn sử dụng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($giaoBuItems as $index => $item)
                                        <tr>
                                            <td class="fw-bold">{{ $item->MaNguyenLieu }}</td>
                                            <td>{{ $item->TenNguyenLieu }}</td>
                                            <td class="fw-bold text-warning">{{ $item->SoLuongCanGiaoBu }}</td>
                                            <td>{{ $item->DonViTinh }}</td>
                                            <td>
                                                <input type="hidden" name="giaoBuItems[{{ $index }}][MaNguyenLieu]" value="{{ $item->MaNguyenLieu }}">
                                                <input type="number" min="0" name="giaoBuItems[{{ $index }}][SoLuongThucNhan]" class="form-control" value="{{ $item->SoLuongCanGiaoBu }}" required>
                                            </td>
                                            <td>
                                                <input type="number" min="0" name="giaoBuItems[{{ $index }}][SoLuongLoi]" class="form-control" value="0" required>
                                            </td>
                                            <td>
                                                <input type="date" name="giaoBuItems[{{ $index }}][NgaySanXuat]" class="form-control" value="{{ old('giaoBuItems.'.$index.'.NgaySanXuat') }}" required>
                                            </td>
                                            <td>
                                                <input type="date" name="giaoBuItems[{{ $index }}][HanSuDung]" class="form-control" value="{{ old('giaoBuItems.'.$index.'.HanSuDung') }}" required>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($doiTraItems->count() > 0)
                <div class="col-lg-12 mb-4">
                    <div class="card page-card">
                        <div class="card-header bg-white border-0 pt-4 px-4">
                            <h5 class="mb-1 fw-bold">Nhận hàng đổi trả</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="col-2">Mã nguyên liệu</th>
                                            <th class="col-2">Tên nguyên liệu</th>
                                            <th class="col-1">Số lượng đổi trả</th>
                                            <th class="col-1">Đơn vị</th>
                                            <th class="col-1">Số lượng thực nhận</th>
                                            <th class="col-1">Số lượng lỗi</th>
                                            <th class="col-2">Ngày sản xuất</th>
                                            <th class="col-2">Hạn sử dụng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($doiTraItems as $index => $item)
                                        <tr>
                                            <td class="fw-bold">{{ $item->MaNguyenLieu }}</td>
                                            <td>{{ $item->TenNguyenLieu }}</td>
                                            <td class="fw-bold text-danger">{{ $item->SoLuongCanDoi }}</td>
                                            <td>{{ $item->DonViTinh }}</td>
                                            <td>
                                                <input type="hidden" name="doiTraItems[{{ $index }}][MaNguyenLieu]" value="{{ $item->MaNguyenLieu }}">
                                                <input type="number" min="0" name="doiTraItems[{{ $index }}][SoLuongThucNhan]" class="form-control" value="{{ $item->SoLuongCanDoi }}" required>
                                            </td>
                                            <td>
                                                <input type="number" min="0" name="doiTraItems[{{ $index }}][SoLuongLoi]" class="form-control" value="0" required>
                                            </td>
                                            <td>
                                                <input type="date" name="doiTraItems[{{ $index }}][NgaySanXuat]" class="form-control" value="{{ old('doiTraItems.'.$index.'.NgaySanXuat') }}" required>
                                            </td>
                                            <td>
                                                <input type="date" name="doiTraItems[{{ $index }}][HanSuDung]" class="form-control" value="{{ old('doiTraItems.'.$index.'.HanSuDung') }}" required>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="col-lg-12">
                <div class="card page-card">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold">Chi tiết nguyên liệu nhận hàng</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="col-2">Mã nguyên liệu</th>
                                        <th class="col-2">Tên nguyên liệu</th>
                                        <th class="col-1">Số lượng đặt</th>
                                        <th class="col-1">Đơn vị</th>
                                        <th class="col-1">Số lượng thực nhận</th>
                                        <th class="col-1">Số lượng lỗi</th>
                                        <th class="col-2">Ngày sản xuất</th>
                                        <th class="col-2">Hạn sử dụng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (DB::table('ChiTietDonDatHang as c')->join('NguyenLieu as n', 'n.MaNguyenLieu', '=', 'c.MaNguyenLieu')->select('c.*', 'n.TenNguyenLieu', 'n.DonViTinh')->where('c.MaDonDatHang', $orderData->MaDonDatHang)->get() as $index => $item)
                                    <tr>
                                        <td class="fw-bold">{{ $item->MaNguyenLieu }}</td>
                                        <td>{{ $item->TenNguyenLieu }}</td>
                                        <td>{{ $item->SoLuongDat }}</td>
                                        <td>{{ $item->DonViTinh }}</td>
                                        <td>
                                            <input type="hidden" name="items[{{ $index }}][MaNguyenLieu]" value="{{ $item->MaNguyenLieu }}">
                                            <input type="number" min="0" name="items[{{ $index }}][SoLuongThucNhan]" class="form-control" value="{{ $item->SoLuongDat }}" required>
                                        </td>
                                        <td>
                                            <input type="number" min="0" name="items[{{ $index }}][SoLuongLoi]" class="form-control" value="0" required>
                                        </td>
                                        <td>
                                            <input type="date" name="items[{{ $index }}][NgaySanXuat]" class="form-control" value="{{ old('items.'.$index.'.NgaySanXuat') }}" required>
                                        </td>
                                        <td>
                                            <input type="date" name="items[{{ $index }}][HanSuDung]" class="form-control" value="{{ old('items.'.$index.'.HanSuDung') }}" required>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-lg-12">
            <div class="d-flex flex-wrap gap-2 mt-4">
                <button class="btn btn-lotteria fw-bold" type="submit">Xác nhận tạo phiếu nhận hàng</button>
                <a class="btn btn-outline-secondary" href="{{ route('ds-don-hang.index') }}">Hủy</a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Đảm bảo ngày nhận luôn là ngày hiện tại khi mở form theo giờ local của trình duyệt
        const ngayNhanInput = document.getElementById('NgayNhan');
        if (ngayNhanInput && !ngayNhanInput.value) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const today = `${year}-${month}-${day}`;
            ngayNhanInput.value = today;
            console.log('Set NgayNhan to:', today);
        }

        // Tương tự cho Ngày sản xuất và Hạn sử dụng của các dòng nguyên liệu nếu chưa có giá trị
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const todayStr = `${year}-${month}-${day}`;

        document.querySelectorAll('input[type="date"]').forEach(input => {
            if (!input.value) {
                if (input.name.includes('NgaySanXuat')) {
                    // Mặc định lùi 30 ngày cho NSX
                    const d = new Date();
                    d.setDate(d.getDate() - 30);
                    input.value = d.toISOString().split('T')[0];
                } else if (input.name.includes('HanSuDung')) {
                    // Mặc định tiến 90 ngày cho HSD
                    const d = new Date();
                    d.setDate(d.getDate() + 90);
                    input.value = d.toISOString().split('T')[0];
                }
            }
        });

        // Validation NSX và HSD
        function validateNSX_HSD(row) {
            const nsxInput = row.querySelector('input[name*="[NgaySanXuat]"]');
            const hsdInput = row.querySelector('input[name*="[HanSuDung]"]');
            
            if (nsxInput && hsdInput) {
                const nsxValue = nsxInput.value;
                const hsdValue = hsdInput.value;

                // Reset validation
                nsxInput.setCustomValidity('');
                hsdInput.setCustomValidity('');

                if (nsxValue && hsdValue) {
                        if (nsxValue > todayStr) {
                            nsxInput.setCustomValidity('Ngày sản xuất không được lớn hơn ngày hiện tại');
                        }

                        if (nsxValue >= hsdValue) {
                            hsdInput.setCustomValidity('Ngày sản xuất phải nhỏ hơn Hạn sử dụng');
                        }
                }
            }
        }

        // Validation số lượng thực nhận và lỗi
        function validateQuantity(row) {
            const thucNhanInput = row.querySelector('input[name*="[SoLuongThucNhan]"]');
            const loiInput = row.querySelector('input[name*="[SoLuongLoi]"]');

            if (thucNhanInput && loiInput) {
                const thucNhan = parseInt(thucNhanInput.value) || 0;
                const loi = parseInt(loiInput.value) || 0;

                // Reset validation
                thucNhanInput.setCustomValidity('');
                loiInput.setCustomValidity('');
                thucNhanInput.classList.remove('is-invalid');
                loiInput.classList.remove('is-invalid');

                // Chặn số âm
                if (thucNhan < 0) {
                    thucNhanInput.setCustomValidity('Số lượng thực nhận không được âm');
                    thucNhanInput.classList.add('is-invalid');
                }

                if (loi < 0) {
                    loiInput.setCustomValidity('Số lượng lỗi không được âm');
                    loiInput.classList.add('is-invalid');
                }

                // Số lượng lỗi không lớn hơn thực nhận
                if (loi > thucNhan) {
                    loiInput.setCustomValidity('Số lượng lỗi không được lớn hơn số lượng thực nhận');
                    loiInput.classList.add('is-invalid');
                }
            }
        }

        // Gán sự kiện validate cho tất cả các dòng
        document.querySelectorAll('tbody tr').forEach(row => {
            // Validate NSX/HSD
            const dateInputs = row.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('change', () => validateNSX_HSD(row));
            });
            validateNSX_HSD(row);

            // Validate số lượng
            const thucNhanInput = row.querySelector('input[name*="[SoLuongThucNhan]"]');
            const loiInput = row.querySelector('input[name*="[SoLuongLoi]"]');
            [thucNhanInput, loiInput].forEach(input => {
                if (input) {
                    input.addEventListener('input', () => validateQuantity(row));
                    input.addEventListener('change', () => validateQuantity(row));
                }
            });
            validateQuantity(row);
        });
    });
</script>
@endpush
