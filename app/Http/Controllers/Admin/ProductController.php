<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest; 
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request) 
    {       
        $data = $request->validated();   
        
        // Tạo slug duy nhất
        $data['slug'] = $this->taoSlugDuyNhat($data['name']);
        
        // Tạo SKU duy nhất nếu không có
        $data['sku'] = $data['sku'] ?? $this->taoSkuDuyNhat();
        
        // Thiết lập giá trị mặc định
        $data['stock_quantity'] = $data['stock_quantity'] ?? 0;
        $data['status'] = $data['status'] ?? 'active';
        $data['is_featured'] = $request->boolean('is_featured');
        
        // Xử lý upload ảnh an toàn
        if ($request->hasFile('image')) {
            $data['image'] = $this->xuLyUploadAnh($request->file('image'));
        }
        
        Product::create($data);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)  
    {    
        $data = $request->validated();      
        
        // Tạo slug từ tên nếu tên thay đổi
        if ($data['name'] !== $product->name) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Xử lý checkbox is_featured
        $data['is_featured'] = $request->boolean('is_featured');
        
        // Xử lý upload ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image && file_exists(public_path('storage/' . $product->image))) {
                unlink(public_path('storage/' . $product->image));
            }
            
            // Upload ảnh mới
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            $uploadPath = public_path('storage/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $image->move($uploadPath, $imageName);
            $data['image'] = 'products/' . $imageName;
        }
        
        $product->update($data);       
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }
    
    public function destroy(Product $product)
    {
        // Xóa ảnh an toàn
        $this->xoaAnhCu($product->image);
        
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }
    
    /**
     * Tạo slug duy nhất để tránh trùng lặp
     */
    private function taoSlugDuyNhat($name, $excludeId = null)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;
        
        while ($this->kiemTraSlugTonTai($slug, $excludeId)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Kiểm tra slug đã tồn tại chưa
     */
    private function kiemTraSlugTonTai($slug, $excludeId = null)
    {
        $query = Product::where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }
    
    /**
     * Tạo SKU duy nhất
     */
    private function taoSkuDuyNhat()
    {
        do {
            $sku = 'SP-' . strtoupper(Str::random(8));
        } while (Product::where('sku', $sku)->exists());
        
        return $sku;
    }
    
    /**
     * Xử lý upload ảnh an toàn và tránh trùng lặp
     */
    private function xuLyUploadAnh($file)
    {
        // Tạo tên file duy nhất
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Tạo thư mục nếu chưa có
        $uploadPath = public_path('storage/products');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        // Di chuyển file
        $file->move($uploadPath, $fileName);
        
        return 'products/' . $fileName;
    }
    
    /**
     * Xóa ảnh cũ an toàn
     */
    private function xoaAnhCu($imagePath)
    {
        if ($imagePath && file_exists(public_path('storage/' . $imagePath))) {
            try {
                unlink(public_path('storage/' . $imagePath));
            } catch (\Exception $e) {
                // Log lỗi nhưng không dừng quá trình
                \Log::warning('Không thể xóa file ảnh: ' . $imagePath);
            }
        }
    }
}