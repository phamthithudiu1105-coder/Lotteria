{{--
    Component: status-badge
    Usage: <x-status-badge :status="$phieuNhan->TrangThai" />
--}}
@php
    $map = [
        'Chờ phê duyệt' => ['class' => 'pending', 'icon' => '📋', 'label' => 'Chờ phê duyệt'],
        'Từ chối' => ['class' => 'rejected', 'icon' => '❌', 'label' => 'Từ chối'],
        'Đã hủy' => ['class' => 'cancelled', 'icon' => '🚫', 'label' => 'Đã hủy'],
        'Chờ nhận hàng' => ['class' => 'approved', 'icon' => '🕐', 'label' => 'Chờ nhận hàng'],
        'Đã nhận hàng' => ['class' => 'received', 'icon' => '✅', 'label' => 'Hoàn tất'],
        'Chờ xử lý' => ['class' => 'processing', 'icon' => '⚠', 'label' => 'Chờ xử lý'],
        'Đang đổi trả' => ['class' => 'processing', 'icon' => '🔄', 'label' => 'Đang đổi trả'],
        'Đã nhập kho' => ['class' => 'stocked', 'icon' => '📥', 'label' => 'Đã nhập kho'],
        'Hoàn tất' => ['class' => 'received', 'icon' => '✅', 'label' => 'Hoàn tất'],
        'Đang xử lý' => ['class' => 'processing', 'icon' => '⏳', 'label' => 'Đang xử lý'],
        'Đã xử lý' => ['class' => 'stocked', 'icon' => '✔', 'label' => 'Đã xử lý'],
        'Chờ nhập' => ['class' => 'pending', 'icon' => '📦', 'label' => 'Chờ nhập'],
        'Đã nhập' => ['class' => 'stocked', 'icon' => '✔', 'label' => 'Đã nhập'],
        'Còn hạn' => ['class' => 'received', 'icon' => '✔', 'label' => 'Còn hạn'],
        'Cận hạn' => ['class' => 'processing', 'icon' => '⚠', 'label' => 'Cận hạn'],
        'Hết hạn' => ['class' => 'rejected', 'icon' => '✖', 'label' => 'Hết hạn'],
        'Đổi hàng' => ['class' => 'processing', 'icon' => '🔄', 'label' => 'Đổi hàng'],
        'Trả hàng' => ['class' => 'rejected', 'icon' => '↩', 'label' => 'Trả hàng'],
    ];
    $cfg = $map[$status] ?? ['class' => 'pending', 'icon' => '–', 'label' => $status];
@endphp
<span class="status-badge {{ $cfg['class'] }}">{{ $cfg['icon'] }} {{ $cfg['label'] }}</span>
