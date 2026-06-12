@extends('layouts.app')

@section('title', 'Tạo đơn đặt hàng')

@php
    $managerMode = request()->routeIs('don-hang.*');
    $routePrefix = $managerMode ? 'don-hang' : 'purchase-orders';
    $oldItems = old('items', $suggestedIngredients->map(fn($item) => ['MaNguyenLieu' => $item->MaNguyenLieu, 'SoLuongDat' => 1])->all());
    $itemCount = count($oldItems);
    $selectedAccount = (string) old('MaTaiKhoan', $currentAccountCode ?? '');
@endphp

@section('content')
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
    <div>
        <h2 class="text-lotteria fw-bold mb-1">Tạo đơn đặt hàng</h2>
    </div>
    <a class="btn btn-outline-secondary" href="{{ route($routePrefix . '.index') }}">Quay lại trang Đơn hàng</a>
</div>

<form method="post" action="{{ route($routePrefix . '.store') }}">
    @csrf

    <div class="card page-card mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h5 class="mb-1 fw-bold">Thông tin đơn hàng</h5>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="NgayDat" class="form-label fw-semibold">Ngày đặt</label>
                    <input id="NgayDat" type="date" name="NgayDat" class="form-control" value="{{ old('NgayDat', now()->toDateString()) }}" readonly>
                </div>
                <div class="col-md-4">
                    <label for="MaTaiKhoan" class="form-label fw-semibold">Người lập đơn</label>
                    <input id="MaTaiKhoan" type="text" class="form-control" value="{{ auth()->user()->MaTaiKhoan }} - {{ auth()->user()->HoTen }} ({{ auth()->user()->VaiTro }})" readonly>
                </div>
                <div class="col-md-4">
                    <label for="GhiChu" class="form-label fw-semibold">Ghi chú</label>
                    <input id="GhiChu" name="GhiChu" maxlength="255" class="form-control" value="{{ old('GhiChu') }}" placeholder="Ví dụ: đơn gấp cho ca sáng">
                </div>
            </div>
        </div>
    </div>

    <div class="card page-card">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1 fw-bold">Nguyên liệu cần đặt</h5>
            </div>
            <button class="btn btn-outline-secondary" type="button" onclick="addItemRow()">+ Thêm dòng</button>
        </div>
        <div class="card-body px-4 pb-4">
            <div id="items" data-next-index="{{ $itemCount }}" class="d-flex flex-column gap-3">
                @foreach ($oldItems as $index => $oldItem)
                    <div class="row g-3 align-items-end border rounded-4 p-3 item-row">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nguyên liệu</label>
                            <select name="items[{{ $index }}][MaNguyenLieu]" class="form-select" required>
                                <option value="">Chọn nguyên liệu</option>
                                @foreach ($ingredients as $ingredient)
                                    <option value="{{ $ingredient->MaNguyenLieu }}" {{ ($oldItem['MaNguyenLieu'] ?? '') === $ingredient->MaNguyenLieu ? 'selected' : '' }}>
                                        {{ $ingredient->MaNguyenLieu }} - {{ $ingredient->TenNguyenLieu }} | Tồn: {{ $ingredient->SoLuongTonKho }} {{ $ingredient->DonViTinh }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Số lượng</label>
                            <input type="number" min="1" max="500" name="items[{{ $index }}][SoLuongDat]" class="form-control" value="{{ $oldItem['SoLuongDat'] ?? 1 }}" required>
                        </div>
                        <div class="col-md-1 d-grid">
                            <button class="btn btn-outline-danger" type="button" onclick="removeItemRow(this)" title="Xóa dòng">X</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <button class="btn btn-lotteria fw-bold" type="submit">Hoàn tất</button>
                <a class="btn btn-outline-secondary" href="{{ route($routePrefix . '.index') }}">Hủy</a>
            </div>
        </div>
    </div>
</form>

<template id="item-template">
    <div class="row g-3 align-items-end border rounded-4 p-3 item-row">
        <div class="col-md-8">
            <label class="form-label fw-semibold">Nguyên liệu</label>
            <select data-name="MaNguyenLieu" class="form-select" required>
                <option value="">Chọn nguyên liệu</option>
                @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->MaNguyenLieu }}">
                        {{ $ingredient->MaNguyenLieu }} - {{ $ingredient->TenNguyenLieu }} | Tồn: {{ $ingredient->SoLuongTonKho }} {{ $ingredient->DonViTinh }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Số lượng</label>
            <input data-name="SoLuongDat" type="number" min="1" max="500" value="1" class="form-control" required>
        </div>
        <div class="col-md-1 d-grid">
            <button class="btn btn-outline-danger" type="button" onclick="removeItemRow(this)" title="Xóa dòng">X</button>
        </div>
    </div>
</template>

<script id="ingredient-options-data" type="application/json">
    @json($ingredients->map(function ($ingredient) {
        return [
            'value' => $ingredient->MaNguyenLieu,
            'label' => $ingredient->MaNguyenLieu . ' - ' . $ingredient->TenNguyenLieu . ' | Tồn: ' . $ingredient->SoLuongTonKho . ' ' . $ingredient->DonViTinh,
        ];
    })->values())
</script>

<script>
    const itemsContainer = document.getElementById('items');
    const ingredientOptionsElement = document.getElementById('ingredient-options-data');
    const ingredientOptions = JSON.parse(ingredientOptionsElement.textContent || '[]');
    let itemIndex = Number(itemsContainer.dataset.nextIndex || 0);

    function validateQuantity(input) {
        const value = parseInt(input.value);
        let errorMsg = '';
        
        if (isNaN(value) || value <= 0) {
            errorMsg = 'Số lượng phải lớn hơn 0';
        } else if (value > 500) {
            errorMsg = 'Số lượng không được vượt quá 500';
        }

        if (errorMsg) {
            input.setCustomValidity(errorMsg);
            input.classList.add('is-invalid');
        } else {
            input.setCustomValidity('');
            input.classList.remove('is-invalid');
        }
    }

    function getSelectedIngredients() {
        const selected = [];
        document.querySelectorAll('.item-row select[name*="MaNguyenLieu"]').forEach(select => {
            if (select.value) {
                selected.push(select.value);
            }
        });
        return selected;
    }

    function updateSelectOptions() {
        const selectedIngredients = getSelectedIngredients();
        document.querySelectorAll('.item-row select[name*="MaNguyenLieu"]').forEach(select => {
            const currentValue = select.value;
            select.innerHTML = '';
            
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Chọn nguyên liệu';
            select.appendChild(placeholder);

            ingredientOptions.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option.value;
                optionElement.textContent = option.label;
                
                if (option.value === currentValue) {
                    optionElement.selected = true;
                    select.appendChild(optionElement);
                } else if (!selectedIngredients.includes(option.value)) {
                    select.appendChild(optionElement);
                }
            });
        });
    }

    // Thêm sự kiện cho tất cả input số lượng hiện có
    document.querySelectorAll('input[name*="SoLuongDat"]').forEach(input => {
        input.addEventListener('input', function() {
            validateQuantity(this);
        });
        validateQuantity(input);
    });

    // Thêm sự kiện change cho tất cả select hiện có
    document.querySelectorAll('select[name*="MaNguyenLieu"]').forEach(select => {
        select.addEventListener('change', updateSelectOptions);
    });

    function addItemRow() {
        const template = document.getElementById('item-template').content.cloneNode(true);
        template.querySelectorAll('[data-name]').forEach((input) => {
            input.name = 'items[' + itemIndex + '][' + input.dataset.name + ']';
            input.removeAttribute('data-name');
        });
        const quantityInput = template.querySelector('input[type="number"]');
        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                validateQuantity(this);
            });
        }
        const selectInput = template.querySelector('select[name*="MaNguyenLieu"]');
        if (selectInput) {
            selectInput.addEventListener('change', updateSelectOptions);
        }
        itemsContainer.appendChild(template);
        itemIndex++;
        itemsContainer.dataset.nextIndex = String(itemIndex);
        updateSelectOptions();
    }

    function removeItemRow(button) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length === 1) {
            rows[0].querySelector('select').value = '';
            const quantityInput = rows[0].querySelector('input[type="number"]');
            quantityInput.value = 1;
            validateQuantity(quantityInput);
        } else {
            button.closest('.item-row').remove();
        }
        updateSelectOptions();
    }

    // Khởi tạo select options ban đầu
    document.addEventListener('DOMContentLoaded', updateSelectOptions);
</script>
@endsection
