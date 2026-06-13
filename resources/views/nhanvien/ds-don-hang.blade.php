@extends('layouts.app')

@section('title', 'Danh sách đơn hàng cần nhận')

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="text-lotteria fw-bold mb-1">Danh sách đơn hàng cần nhận</h2>
    </div>
</div>

<div class="card page-card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Người tạo</th>
                        <th>Trạng thái</th>
                        <th>Số mặt hàng</th>
                        <th>Tổng số lượng</th>
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
                            <td>{{ $order->TongSoLuong }}</td>
                            <td class="text-end">
                                <a class="btn btn-lotteria btn-sm fw-bold" href="{{ route('ds-don-hang.show', $order->MaDonDatHang) }}">Tiếp nhận</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Không có đơn hàng nào cần nhận.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
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
        @endif
    </div>
</div>
@endsection
