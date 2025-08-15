{{-- filepath: resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin.app')

@section('title', 'Cập nhật sản phẩm: ' . $product->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-edit"></i> Cập nhật sản phẩm: <strong>{{ $product->name }}</strong>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
                
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        {{-- Thông tin cơ bản --}}
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Thông tin cơ bản</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="slug">URL slug</label>
                                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                                           id="slug" name="slug" value="{{ old('slug', $product->slug) }}" readonly>
                                                    <small class="form-text text-muted">Tự động tạo từ tên sản phẩm</small>
                                                    @error('slug')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sku">Mã sản phẩm (SKU)</label>
                                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                                           id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                                                    <small class="form-text text-muted">Để trống để tự động tạo</small>
                                                    @error('sku')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="category_id">Danh mục <span class="text-danger">*</span></label>
                                                    <select class="form-control @error('category_id') is-invalid @enderror" 
                                                            id="category_id" name="category_id" required>
                                                        <option value="">Chọn danh mục</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" 
                                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('category_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="short_description">Mô tả ngắn</label>
                                            <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                                      id="short_description" name="short_description" rows="3" 
                                                      maxlength="500">{{ old('short_description', $product->short_description) }}</textarea>
                                            <small class="form-text text-muted">
                                                <span id="short_desc_count">{{ strlen($product->short_description ?? '') }}</span>/500 ký tự
                                            </small>
                                            @error('short_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Mô tả chi tiết</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                      id="description" name="description" rows="8">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Giá cả và tồn kho --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4 class="card-title">Giá cả & Tồn kho</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="price">Giá gốc <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                                               id="price" name="price" value="{{ old('price', $product->price) }}" 
                                                               min="0" step="1000" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">đ</span>
                                                        </div>
                                                    </div>
                                                    @error('price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="sale_price">Giá khuyến mãi</label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                                               id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" 
                                                               min="0" step="1000">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">đ</span>
                                                        </div>
                                                    </div>
                                                    <small class="form-text text-muted">Phải nhỏ hơn giá gốc</small>
                                                    @error('sale_price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="stock_quantity">Số lượng tồn kho</label>
                                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                                           id="stock_quantity" name="stock_quantity" 
                                                           value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0">
                                                    @error('stock_quantity')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Trạng thái tồn kho</label>
                                                    <div class="form-control-plaintext">
                                                        <span class="badge badge-{{ $product->stock_quantity > 10 ? 'success' : ($product->stock_quantity > 0 ? 'warning' : 'danger') }}">
                                                            {{ $product->stock_status }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Sidebar --}}
                            <div class="col-md-4">
                                {{-- Hình ảnh --}}
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Hình ảnh sản phẩm</h4>
                                    </div>
                                    <div class="card-body text-center">
                                        {{-- Ảnh hiện tại --}}
                                        @if($product->image)
                                            <div class="mb-3">
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="img-fluid rounded" 
                                                     id="currentImage"
                                                     style="max-height: 200px;">
                                                <p class="text-muted mt-2">Ảnh hiện tại</p>
                                            </div>
                                        @else
                                            <div class="mb-3">
                                                <img src="{{ asset('images/no-image.jpg') }}" 
                                                     alt="Không có ảnh" 
                                                     class="img-fluid rounded" 
                                                     id="currentImage"
                                                     style="max-height: 200px;">
                                                <p class="text-muted mt-2">Chưa có ảnh</p>
                                            </div>
                                        @endif

                                        {{-- Upload ảnh mới --}}
                                        <div class="form-group">
                                            <label for="image">Chọn ảnh mới</label>
                                            <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                                   id="image" name="image" accept="image/*">
                                            <small class="form-text text-muted">Tối đa 2MB (JPG, PNG, GIF, WebP)</small>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Preview ảnh mới --}}
                                        <div id="imagePreview" class="mt-3" style="display: none;">
                                            <img id="previewImg" class="img-fluid rounded" style="max-height: 200px;">
                                            <p class="text-success mt-2">Ảnh mới (chưa lưu)</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cài đặt --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4 class="card-title">Cài đặt</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="status">Trạng thái</label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" name="status" required>
                                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                                    Hoạt động
                                                </option>
                                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                                    Tạm dừng
                                                </option>
                                                <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>
                                                    Nháp
                                                </option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" 
                                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                <strong>Sản phẩm nổi bật</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                {{-- Thông tin thêm --}}
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4 class="card-title">Thông tin</h4>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Lượt xem:</strong> {{ number_format($product->views) }}</p>
                                        <p><strong>Ngày tạo:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                                        <p><strong>Cập nhật:</strong> {{ $product->updated_at->format('d/m/Y H:i') }}</p>
                                        @if($product->is_on_sale)
                                            <p><strong>Giảm giá:</strong> 
                                                <span class="badge badge-success">{{ $product->discount_percent }}%</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Cập nhật sản phẩm
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Hủy bỏ
                        </a>
                        <button type="button" class="btn btn-warning btn-lg" id="resetForm">
                            <i class="fas fa-undo"></i> Khôi phục
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate slug từ tên sản phẩm
    $('#name').on('input', function() {
        let name = $(this).val();
        let slug = name
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Loại bỏ ký tự đặc biệt
            .replace(/\s+/g, '-')         // Thay khoảng trắng bằng dấu gạch ngang
            .replace(/-+/g, '-')          // Loại bỏ dấu gạch ngang trùng lặp
            .trim('-');                   // Loại bỏ dấu gạch ngang ở đầu/cuối
        $('#slug').val(slug);
    });

    // Đếm ký tự mô tả ngắn
    $('#short_description').on('input', function() {
        let count = $(this).val().length;
        $('#short_desc_count').text(count);
        
        if (count > 450) {
            $('#short_desc_count').addClass('text-warning');
        } else {
            $('#short_desc_count').removeClass('text-warning');
        }
    });

    // Preview ảnh
    $('#image').on('change', function() {
        let file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImg').attr('src', e.target.result);
                $('#imagePreview').show();
            }
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').hide();
        }
    });

    // Validate giá khuyến mãi
    $('#sale_price').on('input', function() {
        let salePrice = parseFloat($(this).val()) || 0;
        let price = parseFloat($('#price').val()) || 0;
        
        if (salePrice > 0 && salePrice >= price) {
            $(this).addClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
            $(this).after('<div class="invalid-feedback">Giá khuyến mãi phải nhỏ hơn giá gốc</div>');
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });

    // Reset form
    $('#resetForm').on('click', function() {
        if (confirm('Bạn có chắc chắn muốn khôi phục lại dữ liệu ban đầu?')) {
            location.reload();
        }
    });

    // Confirm trước khi submit
    $('#productForm').on('submit', function(e) {
        if (!confirm('Bạn có chắc chắn muốn cập nhật sản phẩm này?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush