@extends('layouts.app')

@section('title', $product->name . ' - IQ Media')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            {{-- Hình ảnh sản phẩm --}}
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="img-fluid rounded" 
                     alt="{{ $product->name }}">
            @else
                <div class="bg-light p-5 text-center rounded">
                    <i class="bi bi-box" style="font-size: 5rem; color: #ccc;"></i>
                    <p class="text-muted mt-3">Chưa có hình ảnh</p>
                </div>
            @endif
        </div>
        
        <div class="col-md-6">
            {{-- Thông tin sản phẩm --}}
            <div class="mb-3">
                <span class="badge bg-primary">{{ $product->category->name }}</span>
            </div>
            
            <h1 class="mb-3">{{ $product->name }}</h1>
            
            <div class="mb-4">
                @if($product->sale_price)
                    <span class="text-decoration-line-through text-muted h5">
                        {{ number_format($product->price) }}đ
                    </span>
                    <br>
                    <span class="text-danger fw-bold h3">
                        {{ number_format($product->sale_price) }}đ
                    </span>
                @else
                    <span class="text-primary fw-bold h3">
                        {{ number_format($product->price) }}đ
                    </span>
                @endif
            </div>
            
            @if($product->sku)
                <p class="text-muted">SKU: {{ $product->sku }}</p>
            @endif
            
            @if($product->short_description)
                <div class="mb-4">
                    <h5>Mô tả ngắn:</h5>
                    <p>{{ $product->short_description }}</p>
                </div>
            @endif
            
            <div class="mb-4">
                <form action="{{ route('user.cart.add-cart-item',$product->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-lg me-2">
                        <i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng
                    </button>
                </form>
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-heart"></i>
                </button>
            </div>
        </div>
    </div>
    
    @if($product->description)
        <div class="row mt-5">
            <div class="col-12">
                <h3>Mô tả chi tiết</h3>
                <div class="content">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection