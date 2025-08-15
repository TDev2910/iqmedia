<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách tất cả sản phẩm
     * URL: /collections/all
     */
    public function index(Request $request)
    {
        // Khởi tạo query cơ bản
        $query = Product::with(['category', 'primaryImage'])
            ->active()
            ->ordered();
        
        // Lọc theo danh mục nếu có
        $this->locTheoDanhMuc($query, $request);
        
        // Phân trang
        $soLuongTrenTrang = $request->get('per_page', 12);
        $products = $query->paginate($soLuongTrenTrang);
        
        // Lấy dữ liệu phụ trợ
        $categories = Category::active()->ordered()->get();
        $featuredProducts = $this->laySanPhamNoiBat();
        
        return view('products.index', compact('products', 'categories', 'featuredProducts'));
    }

    /**
     * Hiển thị chi tiết sản phẩm theo slug
     * URL: /{product-slug}
     */
    public function show($slug)
    {
        // Tìm sản phẩm theo slug
        $product = Product::with(['category', 'images'])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        // Tăng lượt xem
        $this->tangLuotXem($product);

        // Lấy sản phẩm liên quan
        $relatedProducts = $this->laySanPhamLienQuan($product);
        $similarProducts = $this->laySanPhamTuongTu($product);

        return view('products.show', compact('product', 'relatedProducts', 'similarProducts'));
    }

    /**
     * Tìm kiếm sản phẩm qua AJAX
     */
    public function search(Request $request)
    {
        $tuKhoa = trim($request->get('q', ''));
        
        if (empty($tuKhoa)) {
            return response()->json([]);
        }

        $products = Product::with(['category', 'primaryImage'])
            ->active()
            ->where('name', 'like', '%' . $tuKhoa . '%')
            ->limit(10)
            ->get();

        return response()->json($this->chuyenDoiDuLieuTimKiem($products));
    }

    /**
     * Hiển thị sản phẩm theo danh mục
     */
    public function category($categorySlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        $products = Product::where('category_id', $category->id)
            ->active()
            ->with(['category', 'primaryImage'])
            ->latest()
            ->paginate(12);
            
        return view('products.category', compact('products', 'category'));
    }

    /**
     * Lấy danh sách sản phẩm nổi bật qua API
     */
    public function featured()
    {
        $products = Product::with('category')
            ->active()
            ->featured()
            ->limit(12)
            ->get();
            
        return response()->json($products);
    }

    // ===== CÁC PHƯƠNG THỨC HỖ TRỢ PRIVATE =====

    /**
     * Lọc sản phẩm theo danh mục
     */
    private function locTheoDanhMuc($query, $request)
    {
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
    }

    /**
     * Lấy sản phẩm nổi bật
     */
    private function laySanPhamNoiBat()
    {
        return Product::with(['category', 'primaryImage'])
            ->active()
            ->featured()
            ->limit(8)
            ->get();
    }

    /**
     * Tăng lượt xem sản phẩm một cách an toàn
     */
    private function tangLuotXem($product)
    {
        try {
            $product->increment('views');
        } catch (\Exception $e) {
            // Không làm gián đoạn quá trình nếu không tăng được lượt xem
            \Log::warning('Không thể tăng lượt xem cho sản phẩm ID: ' . $product->id);
        }
    }

    /**
     * Lấy sản phẩm liên quan (cùng danh mục)
     */
    private function laySanPhamLienQuan($product)
    {
        return Product::with(['category', 'primaryImage'])
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(8)
            ->get();
    }

    /**
     * Lấy sản phẩm tương tự (cải thiện thuật toán)
     */
    private function laySanPhamTuongTu($product)
    {
        // Tách từ khóa từ tên sản phẩm
        $tuKhoa = $this->tachTuKhoa($product->name);
        
        if (empty($tuKhoa)) {
            return collect(); // Trả về collection rỗng
        }

        $query = Product::with(['category', 'primaryImage'])
            ->active()
            ->where('id', '!=', $product->id);

        // Tìm kiếm theo từng từ khóa
        foreach ($tuKhoa as $tu) {
            $query->orWhere('name', 'like', '%' . $tu . '%');
        }

        return $query->limit(4)->get();
    }

    /**
     * Tách từ khóa từ tên sản phẩm
     */
    private function tachTuKhoa($tenSanPham)
    {
        // Loại bỏ các từ phổ biến không cần thiết
        $tuLoaiBo = ['cho', 'của', 'và', 'với', 'từ', 'tại', 'trong', 'trên'];
        
        $tuKhoa = explode(' ', strtolower($tenSanPham));
        
        // Lọc bỏ từ ngắn và từ không cần thiết
        $tuKhoa = array_filter($tuKhoa, function($tu) use ($tuLoaiBo) {
            return strlen($tu) > 2 && !in_array($tu, $tuLoaiBo);
        });

        return array_slice($tuKhoa, 0, 3); // Chỉ lấy 3 từ khóa đầu tiên
    }

    /**
     * Chuyển đổi dữ liệu cho kết quả tìm kiếm
     */
    private function chuyenDoiDuLieuTimKiem($products)
    {
        return $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->formatted_price,
                'sale_price' => $product->formatted_sale_price,
                'image' => $product->primaryImage 
                    ? asset('storage/' . $product->primaryImage->image_path) 
                    : null,
                'category' => $product->category?->name,
                'url' => route('products.show', $product->slug)
            ];
        });
    }
}
