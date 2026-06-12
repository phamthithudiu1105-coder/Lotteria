@extends('layouts.app')

@section('title', 'Xử lý đơn hàng ' . $order->MaDonDatHang)

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="text-lotteria fw-bold mb-1">Xử lý đơn {{ $order->MaDonDatHang }}</h2>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route('don-hang.show', $order->MaDonDatHang) }}">Quay lại</a>
</div>

<form method="post" action="{{ route('don-hang.resolve.store', $order->MaDonDatHang) }}" class="card page-card mb-4">
    @csrf
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="mb-1 fw-bold">Chi tiết xử lý đơn hàng</h5>
    </div>
    <div class="card-body px-4 pb-4">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã NL</th>
                        <th>Tên nguyên liệu</th>
                        <th>Số đặt</th>
                        <th>Thực nhận</th>
                        <th>Lỗi</th>
                        <th>Hàng tốt</th>
                        <th>Thừa</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        @php
                            $soLuongThieu = max(0, $item->SoLuongDat - $item->SoLuongTot);
                        @endphp
                        <tr>
                            <td class="fw-bold">{{ $item->MaNguyenLieu }}</td>
                            <td>{{ $item->TenNguyenLieu }}</td>
                            <td>{{ $item->SoLuongDat }} {{ $item->DonViTinh }}</td>
                            <td>{{ $item->SoLuongThucNhan }} {{ $item->DonViTinh }}</td>
                            <td>{{ $item->SoLuongLoi }} {{ $item->DonViTinh }}</td>
                            <td>{{ $item->SoLuongTot }} {{ $item->DonViTinh }}</td>
                            <td>{{ $item->SoLuongThua }} {{ $item->DonViTinh }}</td>
                            <td>
                                @php
                                    $hasThieuOrThua = $soLuongThieu > 0 || $item->SoLuongThua > 0;
                                @endphp
                                @if($soLuongThieu > 0)
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-warning">Xử lý thiếu ({{ $soLuongThieu }}):</label>
                                        <select name="items[{{ $item->MaNguyenLieu }}][LoaiXuLyThieu]" class="form-select xu-ly-thieu" data-nguyen-lieu="{{ $item->MaNguyenLieu }}" required>
                                            <option value="">Chọn</option>
                                            <option value="giao_bu">Yêu cầu giao bù</option>
                                            <option value="huy">Hủy phần thiếu</option>
                                        </select>
                                    </div>
                                @endif
                                @if($item->SoLuongThua > 0)
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-danger">Xử lý thừa ({{ $item->SoLuongThua }}):</label>
                                        <select name="items[{{ $item->MaNguyenLieu }}][LoaiXuLyThua]" class="form-select xu-ly-thua" data-nguyen-lieu="{{ $item->MaNguyenLieu }}" required>
                                            <option value="">Chọn</option>
                                            <option value="nhap_toan_bo">Nhập toàn bộ</option>
                                            <option value="tra">Trả phần thừa</option>
                                        </select>
                                    </div>
                                @endif
                                @if($item->SoLuongLoi > 0)
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold text-danger">Xử lý lỗi ({{ $item->SoLuongLoi }}):</label>
                                        <select name="items[{{ $item->MaNguyenLieu }}][LoaiXuLyLoi]" class="form-select xu-ly-loi" data-nguyen-lieu="{{ $item->MaNguyenLieu }}" required @if($hasThieuOrThua) disabled @endif>
                                            <option value="">Chọn</option>
                                            <option value="tra" selected>Trả hàng lỗi</option>
                                            <option value="doi">Yêu cầu đổi hàng</option>
                                        </select>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row g-3 mt-4">
            <div class="col-12">
                <label for="GhiChu" class="form-label fw-semibold">Ghi chú xử lý</label>
                <textarea id="GhiChu" name="GhiChu" rows="3" maxlength="255" class="form-control" placeholder="Nhập ghi chú (nếu có)">{{ old('GhiChu') }}</textarea>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-4">
            <button class="btn btn-lotteria fw-bold" type="submit">Xác nhận xử lý</button>
            <a class="btn btn-outline-secondary" href="{{ route('don-hang.show', $order->MaDonDatHang) }}">Hủy</a>
        </div>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Process each row
        @foreach($items as $item)
            @php
                $soLuongThieu = max(0, $item->SoLuongDat - $item->SoLuongTot);
                $hasThieuOrThua = $soLuongThieu > 0 || $item->SoLuongThua > 0;
            @endphp
            @if($hasThieuOrThua && $item->SoLuongLoi > 0)
                // If there's both, add a hidden input to send the value
                (function() {
                    const maNguyenLieu = '{{ $item->MaNguyenLieu }}';
                    const loiSelect = document.querySelector('.xu-ly-loi[data-nguyen-lieu="' + maNguyenLieu + '"]');
                    if (loiSelect) {
                        // Create a hidden input to ensure the value is sent
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'items[' + maNguyenLieu + '][LoaiXuLyLoi]';
                        hiddenInput.value = 'tra';
                        loiSelect.closest('td').appendChild(hiddenInput);
                    }
                })();
            @endif
        @endforeach

        // Also handle dynamic change
        function updateLoiSelect(maNguyenLieu) {
            const thieuSelect = document.querySelector('.xu-ly-thieu[data-nguyen-lieu="' + maNguyenLieu + '"]');
            const thuaSelect = document.querySelector('.xu-ly-thua[data-nguyen-lieu="' + maNguyenLieu + '"]');
            const loiSelect = document.querySelector('.xu-ly-loi[data-nguyen-lieu="' + maNguyenLieu + '"]');
            
            if (loiSelect) {
                const hasThieuOrThua = (thieuSelect && thieuSelect.value !== '') || (thuaSelect && thuaSelect.value !== '');
                
                if (hasThieuOrThua) {
                    loiSelect.value = 'tra';
                    loiSelect.disabled = true;
                    
                    // Add hidden input if needed
                    let hiddenInput = loiSelect.closest('td').querySelector('input[type="hidden"][name="items[' + maNguyenLieu + '][LoaiXuLyLoi]"]');
                    if (!hiddenInput) {
                        hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'items[' + maNguyenLieu + '][LoaiXuLyLoi]';
                        loiSelect.closest('td').appendChild(hiddenInput);
                    }
                    hiddenInput.value = 'tra';
                } else {
                    loiSelect.disabled = false;
                    
                    // Remove hidden input
                    const hiddenInput = loiSelect.closest('td').querySelector('input[type="hidden"][name="items[' + maNguyenLieu + '][LoaiXuLyLoi]"]');
                    if (hiddenInput) {
                        hiddenInput.remove();
                    }
                }
            }
        }

        // Add event listeners
        document.querySelectorAll('.xu-ly-thieu, .xu-ly-thua').forEach(select => {
            select.addEventListener('change', function() {
                const maNguyenLieu = this.dataset.nguyenLieu;
                updateLoiSelect(maNguyenLieu);
            });
        });
    });
</script>
@endpush
@endsection
