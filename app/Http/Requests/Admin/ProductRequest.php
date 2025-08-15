<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        //kiem tr admin role
        return Auth::check() && Auth::user()->role === 'admin';
    }

    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        
        return [
            'name' => 'required|string|max:255|min:2',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $productId . '|regex:/^[a-z0-9-]+$/',
            'description' => 'nullable|string|max:10000',
            'short_description' => 'nullable|string|max:500',        
            
            // Validation giá cả cải tiến
            'price' => 'required|numeric|min:1000|max:999999999',
            'sale_price' => [
                'nullable',
                'numeric', 
                'min:1000',
                function ($attribute, $value, $fail) {
                    $price = $this->input('price');
                    if ($value && $price && $value >= $price) {
                        $fail('Giá khuyến mãi phải nhỏ hơn giá gốc.');
                    }
                },
            ],
            
            // SKU và stock
            'sku' => [
                'nullable',
                'string',
                'max:100',
                'unique:products,sku,' . $productId,
                'regex:/^[A-Z0-9\-]+$/',
            ],
            'stock_quantity' => 'nullable|integer|min:0|max:999999',           
            
            // Category và status
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive,draft',
            'is_featured' => 'nullable|boolean',          
            
            // Image validation cải tiến
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048',
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Tên sản phẩm
            'name.required' => 'Tên sản phẩm là bắt buộc',
            'name.min' => 'Tên sản phẩm phải có ít nhất 2 ký tự',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự',
            
            // Slug
            'slug.unique' => 'URL slug này đã tồn tại',
            'slug.regex' => 'URL slug chỉ được chứa chữ thường, số và dấu gạch ngang',
            
            // Giá cả
            'price.required' => 'Giá sản phẩm là bắt buộc',
            'price.numeric' => 'Giá sản phẩm phải là số',
            'price.min' => 'Giá sản phẩm phải từ 1.000đ trở lên',
            'price.max' => 'Giá sản phẩm không được vượt quá 999.999.999đ',
            
            'sale_price.numeric' => 'Giá khuyến mãi phải là số',
            'sale_price.min' => 'Giá khuyến mãi phải từ 1.000đ trở lên',
            
            // SKU
            'sku.unique' => 'Mã SKU này đã tồn tại',
            'sku.regex' => 'Mã SKU chỉ được chứa chữ hoa, số và dấu gạch ngang',
            
            // Stock
            'stock_quantity.integer' => 'Số lượng tồn kho phải là số nguyên',
            'stock_quantity.min' => 'Số lượng tồn kho không được âm',
            'stock_quantity.max' => 'Số lượng tồn kho không được vượt quá 999.999',
            
            // Category và status
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'status.required' => 'Vui lòng chọn trạng thái',
            'status.in' => 'Trạng thái phải là: Hoạt động, Tạm dừng, hoặc Nháp',
            
            // Hình ảnh
            'image.image' => 'File phải là hình ảnh',
            'image.mimes' => 'Ảnh phải có định dạng: JPEG, PNG, JPG, GIF, WebP',
            'image.max' => 'Ảnh không được vượt quá 2MB',
            'image.dimensions' => 'Ảnh phải có kích thước từ 100x100px đến 2000x2000px',
        ];
    }
}