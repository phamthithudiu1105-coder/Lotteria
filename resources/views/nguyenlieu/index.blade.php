@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">DANH MỤC NGUYÊN LIÊU</h5>
        <form id="upload-form" action="/nguyen-lieu/upload-excel" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" id="file-upload" name="file" accept=".xlsx,.xls" style="display: none;" onchange="document.getElementById('upload-form').submit()">
            <button type="button" onclick="document.getElementById('file-upload').click()" class="btn btn-light btn-sm fw-bold text-danger">+ Thêm mới</button>
        </form>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-hover table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Mã NL</th>
                    <th>Tên nguyên liệu</th>
                    <th>Nhóm hàng</th>
                    <th>Tồn kho</th>
                    <th>Đơn vị</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($danhSachNL as $nl)
                <tr>
                    <td>{{ $nl->MaNguyenLieu }}</td>
                    <td class="fw-bold">{{ $nl->TenNguyenLieu }}</td>
                    <td>
                        <span class="badge {{ $nl->NhomHang === 'Hàng đông' ? 'bg-success' : ($nl->NhomHang === 'Hàng khô' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                            {{ $nl->NhomHang }}
                        </span>
                    </td>
                    <td>{{ number_format($nl->SoLuongTonKho) }}</td>
                    <td>{{ $nl->DonViTinh }}</td>
                    <td class="d-flex gap-1">
                        <form action="/nguyen-lieu/{{ $nl->MaNguyenLieu }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nguyên liệu này khỏi kho?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
