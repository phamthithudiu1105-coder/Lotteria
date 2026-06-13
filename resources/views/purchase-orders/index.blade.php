@extends('layouts.app')

@section('title', 'Trang Đơn Hàng')

@php
    $isManagerUser = auth()->check() && in_array(auth()->user()->VaiTro ?? null, ['Quan ly', 'Quản lý'], true);
    $isStoreChiefUser = auth()->check() && in_array(auth()->user()->VaiTro ?? null, ['Cua hang truong', 'Cửa hàng trưởng'], true);
    $managerMode = request()->routeIs('don-hang.*');
    $routePrefix = $managerMode ? 'don-hang' : 'purchase-orders';
    $statusLabels = [
        'Chờ phê duyệt' => 'Chờ phê duyệt',
        'Chờ nhận hàng' => 'Chờ nhận hàng',
        'Chờ xử lý' => 'Chờ xử lý',
        'Đang đổi trả' => 'Đang đổi trả',
        'Từ chối' => 'Từ chối',
        'Đã hủy' => 'Đã hủy',
        'Đã nhận hàng' => 'Hoàn tất',
        'Hoàn tất' => 'Hoàn tất',
        'Đã nhập kho' => 'Đã nhập kho',
    ];
    $sortIcon = function (string $column) use ($sort, $direction) {
        if ($sort !== $column) {
            return '↕';
        }

        return $direction === 'asc' ? '↑' : '↓';
    };
    $sortUrl = function (string $column) use ($sort, $direction, $search, $status, $routePrefix) {
        $nextDirection = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';

        return route($routePrefix . '.index', array_filter([
            'search' => $search !== '' ? $search : null,
            'status' => $status ?: null,
            'sort' => $column,
            'direction' => $nextDirection,
        ], fn ($value) => $value !== null));
    };
@endphp

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="text-lotteria fw-bold mb-1">{{ $managerMode ? 'Trang Đơn Hàng' : ($isStoreChiefUser ? 'Phê Duyệt Đơn Mua' : 'Đặt hàng & phê duyệt đơn mua') }}</h2>
    </div>
    @if ($isManagerUser && $managerMode)
        <a href="{{ route($routePrefix . '.create') }}" class="btn btn-lotteria fw-bold">+ Tạo đơn đặt hàng</a>
    @endif
</div>

@if ($managerMode)
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card page-card summary-tile h-100">
                <div class="card-body">
                    <div class="text-muted fw-semibold">Chờ phê duyệt</div>
                    <div class="display-6 fw-bold text-warning-emphasis">{{ $managerSummary['Chờ phê duyệt'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card page-card summary-tile h-100">
                <div class="card-body">
                    <div class="text-muted fw-semibold">Chờ xử lý</div>
                    <div class="display-6 fw-bold text-primary">{{ $managerSummary['Chờ xử lý'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card page-card summary-tile h-100">
                <div class="card-body">
                    <div class="text-muted fw-semibold">Hoàn tất</div>
                    <div class="display-6 fw-bold text-success">{{ $managerSummary['Hoàn tất'] ?? $managerSummary['Đã nhận hàng'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card page-card summary-tile h-100">
                <div class="card-body">
                    <div class="text-muted fw-semibold">Đang đổi trả</div>
                    <div class="display-6 fw-bold text-info-emphasis">{{ $managerSummary['Đang đổi trả'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>
@elseif ($isStoreChiefUser)
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card page-card summary-tile h-100">
                <div class="card-body">
                    <div class="text-muted fw-semibold">Chờ phê duyệt</div>
                    <div class="display-6 fw-bold text-warning-emphasis">{{ $summaryCards['Chờ phê duyệt'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card page-card summary-tile h-100">
                <div class="card-body">
                    <div class="text-muted fw-semibold">Đã duyệt</div>
                    <div class="display-6 fw-bold text-success">{{ $summaryCards['Chờ nhận hàng'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card page-card summary-tile h-100">
                <div class="card-body">
                    <div class="text-muted fw-semibold">Từ chối / Hủy</div>
                    <div class="display-6 fw-bold text-danger">{{ ($summaryCards['Từ chối'] ?? 0) + ($summaryCards['Đã hủy'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="card page-card mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end" method="get" action="{{ route($routePrefix . '.index') }}">
            <div class="col-md-5">
                <label for="search" class="form-label fw-semibold">Tìm kiếm</label>
                <input id="search" name="search" class="form-control" value="{{ $search }}" placeholder="Mã đơn, người tạo, ghi chú">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label fw-semibold">Trạng thái</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Tất cả</option>
                    @foreach ($statusOptions as $option)
                        <option value="{{ $option }}" {{ $status === $option ? 'selected' : '' }}>{{ $statusLabels[$option] ?? $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-auto">
                <button class="btn btn-lotteria">Lọc</button>
            </div>
            <div class="col-md-auto">
                <a class="btn btn-outline-secondary" href="{{ route($routePrefix . '.index') }}">Đặt lại</a>
            </div>
        </form>
    </div>
</div>

<div class="card page-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th><a class="text-decoration-none text-dark" href="{{ $sortUrl('code') }}">Mã đơn {{ $sortIcon('code') }}</a></th>
                        <th><a class="text-decoration-none text-dark" href="{{ $sortUrl('date') }}">Ngày đặt {{ $sortIcon('date') }}</a></th>
                        <th>Người tạo</th>
                        <th><a class="text-decoration-none text-dark" href="{{ $sortUrl('status') }}">Trạng thái {{ $sortIcon('status') }}</a></th>
                        <th><a class="text-decoration-none text-dark" href="{{ $sortUrl('items') }}">Mặt hàng {{ $sortIcon('items') }}</a></th>
                        <th><a class="text-decoration-none text-dark" href="{{ $sortUrl('quantity') }}">Tổng SL {{ $sortIcon('quantity') }}</a></th>
                        <th>Ghi chú</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="fw-bold">{{ $order->MaDonDatHang }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($order->NgayDat)->format('d/m/Y') }}</td>
                            <td>{{ $order->HoTen }}</td>
                            <td><x-status-badge :status="$order->TrangThai" /></td>
                            <td>{{ $order->SoMatHang }}</td>
                            <td>{{ number_format($order->TongSoLuong) }}</td>
                            <td>{{ $order->GhiChu ?: '-' }}</td>
                            <td class="text-end">
                                @if ($managerMode)
                                    @if ($order->TrangThai === 'Chờ phê duyệt')
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route($routePrefix . '.edit', $order->MaDonDatHang) }}">Sửa</a>
                                            <form method="post" action="{{ route($routePrefix . '.cancel', $order->MaDonDatHang) }}" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này không?');">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Hủy</button>
                                            </form>
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('don-hang.show', $order->MaDonDatHang) }}">Xem</a>
                                        </div>
                                    @else
                                        <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('don-hang.show', $order->MaDonDatHang) }}">Xem</a>
                                        </div>
                                    @endif
                                @elseif ($isStoreChiefUser)
                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                        @if ($order->TrangThai === 'Chờ phê duyệt')
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('purchase-orders.show', $order->MaDonDatHang) }}">Xem & duyệt</a>
                                        @else
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('purchase-orders.show', $order->MaDonDatHang) }}">Xem lịch sử/chi tiết</a>
                                        @endif
                                    </div>
                                @else
                                    <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('purchase-orders.show', $order->MaDonDatHang) }}">Chi tiết</a>
                                        @if ($order->TrangThai === 'Chờ phê duyệt')
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('purchase-orders.edit', $order->MaDonDatHang) }}">Sửa</a>
                                            <form method="post" action="{{ route('purchase-orders.cancel', $order->MaDonDatHang) }}" onsubmit="return confirm('Bạn có chắc muốn hủy đơn này không?');">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Hủy đơn</button>
                                            </form>
                                        @elseif ($order->TrangThai === 'Chờ nhận hàng')
                                            <form method="post" action="{{ route('purchase-orders.receive', $order->MaDonDatHang) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success" type="submit">Nhận hàng</button>
                                            </form>
                                        @elseif ($order->TrangThai === 'Đã nhận hàng' || $order->TrangThai === 'Hoàn tất')
                                            <form method="post" action="{{ route('purchase-orders.stock', $order->MaDonDatHang) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-success" type="submit">Nhập kho</button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Chưa có đơn hàng phù hợp.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    {{-- Previous Page Link --}}
                    @if ($orders->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">&laquo; Trước</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev">&laquo; Trước</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($orders->links()->elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link">{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $orders->currentPage())
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($orders->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next">Sau &raquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link">Sau &raquo;</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
