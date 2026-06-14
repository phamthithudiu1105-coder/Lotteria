@extends('layouts.app')

@section('title', 'Tạo Phiếu Xuất Kho')

@section('content')
<style>
    .header-title { color: #a52a2a; font-weight: bold; }
    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        max-height: 360px;
        overflow-y: auto;
        border-radius: 0 0 0.375rem 0.375rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .search-item { cursor: pointer; }
    .search-item:hover { background-color: #f8f9fa; }
    .search-help { color: #6c757d; font-size: 0.95rem; }
</style>

@php
    $NguyenLieuHienThi = $danhSachNguyenLieu->where('SoLuongTonKho', '>=', 20)->values();
    $NguyenLieuTimKiem = $danhSachNguyenLieu->values();
@endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="header-title mb-1">Tạo Phiếu Xuất Kho</h4>
    </div>
    <a href="{{ route('xuatkho.index') }}" class="btn btn-outline-secondary">Danh Sách Phiếu</a>
</div>

<div class="card page-card mb-3">
    <div class="card-body">
        <label for="searchInput" class="form-label fw-semibold text-muted">Thêm nguyên liệu khác</label>
        <div class="position-relative">
            <input type="text" id="searchInput" class="form-control form-control-lg border-danger"
                   placeholder="Tìm kiếm nguyên liệu..." autocomplete="off">
            <div id="searchDropdown" class="list-group search-dropdown w-100"></div>
        </div>
        <div class="search-help mt-2">
            Hiện có {{ $NguyenLieuTimKiem->count() }} nguyên liệu có thể tìm kiếm và thêm vào.
        </div>
    </div>
</div>

<form action="{{ route('xuatkho.store') }}" method="POST">
    @csrf
    <div class="card page-card">
        <div class="card-header bg-lotteria d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Danh Sách Nguyên Liệu Chờ Xuất</h6>
            <span class="badge bg-warning text-dark" id="countBadge">{{ $NguyenLieuHienThi->count() }} nguyên liệu</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="selectedTable">
                <thead class="table-light text-danger">
                    <tr>
                        <th>MÃ NL</th>
                        <th>TÊN NGUYÊN LIỆU</th>
                        <th>NHÓM HÀNG</th>
                        <th>TỒN KHO</th>
                        <th width="150">SỐ LƯỢNG XUẤT</th>
                        <th width="80" class="text-center">XÓA</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach($NguyenLieuHienThi as $nl)
                    <tr id="row-{{ $nl->MaNguyenLieu }}">
                        <td>{{ $nl->MaNguyenLieu }}</td>
                        <td class="fw-bold text-danger">{{ $nl->TenNguyenLieu }}</td>
                        <td>{{ $nl->NhomHang }}</td>
                        <td class="text-primary fw-bold">{{ $nl->SoLuongTonKho }} {{ $nl->DonViTinh }}</td>
                        <td>
                            <input type="number" name="nguyen_lieu[{{ $nl->MaNguyenLieu }}]"
                                   class="form-control form-control-sm text-center"
                                   min="0" max="{{ max($nl->SoLuongTonKho, 20) }}" value="20">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="{{ $nl->MaNguyenLieu }}" title="Xóa dòng này">
                                Xóa
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white text-end py-3">
            <button type="button" class="btn btn-outline-secondary me-2" onclick="location.reload()">Làm Mới Trang</button>
            <button type="submit" class="btn btn-danger" style="background-color: #a52a2a;">Xác Nhận Xuất Kho</button>
        </div>
    </div>
</form>

<script>
    const NguyenLieusTimKiem = @json($NguyenLieuTimKiem);
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchDropdown = document.getElementById('searchDropdown');
        const tableBody = document.getElementById('tableBody');
        const countBadge = document.getElementById('countBadge');

        let selectedItems = new Set(@json($NguyenLieuHienThi->pluck('MaNguyenLieu')));
        let totalItems = {{ $NguyenLieuHienThi->count() }};

        // Hàm triệt tiêu dấu tiếng Việt và đưa về chữ thường
        function xoaDauTiengViet(str) {
            if (!str) return "";
            return str.normalize('NFD')
                      .replace(/[\u0300-\u036f]/g, '')
                      .replace(/đ/g, 'd').replace(/Đ/g, 'D')
                      .toLowerCase()
                      .trim();
        }

        function renderDropdown(showAll = false) {
            const keyword = xoaDauTiengViet(searchInput.value);
            searchDropdown.innerHTML = '';

            if (!showAll && keyword.length === 0) {
                searchDropdown.style.display = 'none';
                return;
            }

            const filtered = NguyenLieusTimKiem.filter(nl => {
                if (selectedItems.has(nl.MaNguyenLieu)) {
                    return false;
                }

                if (showAll && keyword.length === 0) {
                    return true;
                }

                const tenNL = xoaDauTiengViet(nl.TenNguyenLieu);
                const maNL = xoaDauTiengViet(nl.MaNguyenLieu);

                return tenNL.includes(keyword) || maNL.includes(keyword);
            });

            if (filtered.length === 0) {
                const message = selectedItems.size === {{ $danhSachNguyenLieu->count() }}
                    ? 'Bạn đã chọn hết nguyên liệu.'
                    : 'Không tìm thấy nguyên liệu chưa chọn...';
                searchDropdown.innerHTML = `<div class="list-group-item text-muted">${message}</div>`;
                searchDropdown.style.display = 'block';
                return;
            }

            filtered.forEach(nl => {
                const item = document.createElement('a');
                item.className = 'list-group-item list-group-item-action search-item d-flex justify-content-between align-items-center';
                item.innerHTML = `
                    <div>
                        <strong>${nl.TenNguyenLieu}</strong>
                        <small class="text-muted">(${nl.MaNguyenLieu})</small>
                        <div class="small text-muted">${nl.NhomHang || ''}</div>
                    </div>
                    <span class="badge bg-primary rounded-pill">Tồn: ${nl.SoLuongTonKho} ${nl.DonViTinh}</span>
                `;

                item.addEventListener('click', function() {
                    addIngredientToTable(nl);
                    searchInput.value = '';
                    renderDropdown(true);
                    searchInput.focus();
                });

                searchDropdown.appendChild(item);
            });

            searchDropdown.style.display = 'block';
        }

        function addIngredientToTable(nl) {
            if (selectedItems.has(nl.MaNguyenLieu)) {
                alert('Nguyên liệu này đã có trong danh sách!');
                return;
            }

            const defaultValue = Math.min(20, nl.SoLuongTonKho);
            const tr = document.createElement('tr');
            tr.id = `row-${nl.MaNguyenLieu}`;
            tr.innerHTML = `
                <td>${nl.MaNguyenLieu}</td>
                <td class="fw-bold text-danger">${nl.TenNguyenLieu}</td>
                <td>${nl.NhomHang}</td>
                <td class="text-primary fw-bold">${nl.SoLuongTonKho} ${nl.DonViTinh}</td>
                <td>
                    <input type="number" name="nguyen_lieu[${nl.MaNguyenLieu}]"
                           class="form-control form-control-sm text-center"
                           min="0" max="${nl.SoLuongTonKho}" value="${defaultValue}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-id="${nl.MaNguyenLieu}" title="Xóa dòng này">
                        Xóa
                    </button>
                </td>
            `;

            tableBody.appendChild(tr);
            selectedItems.add(nl.MaNguyenLieu);
            totalItems++;
            updateBadge();
        }

        tableBody.addEventListener('click', function(e) {
            if (!e.target.classList.contains('btn-delete')) {
                return;
            }

            const maNL = e.target.getAttribute('data-id');
            const row = document.getElementById(`row-${maNL}`);

            if (row) {
                row.remove();
                selectedItems.delete(maNL);
                totalItems--;
                updateBadge();

                if (document.activeElement === searchInput || searchDropdown.style.display === 'block') {
                    renderDropdown(true);
                }
            }
        });

        function updateBadge() {
            countBadge.textContent = `${totalItems} nguyên liệu`;
        }

        searchInput.addEventListener('focus', function() {
            renderDropdown(true);
        });

        searchInput.addEventListener('click', function() {
            renderDropdown(true);
        });

        searchInput.addEventListener('input', function() {
            renderDropdown(false);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.style.display = 'none';
            }
        });
    });
</script>
@endsection