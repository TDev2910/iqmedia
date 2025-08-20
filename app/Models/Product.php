<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price', 
        'sku',
        'stock_quantity',
        'category_id',
        'status',
        'is_featured',
        'image',
        'views'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'stock_quantity' => 'integer',
        'views' => 'integer',
    ];

    // ===== CÁC SCOPE QUERIES =====

    /**
     * Chỉ lấy sản phẩm đang hoạt động
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Sắp xếp theo thời gian tạo mới nhất
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Chỉ lấy sản phẩm nổi bật
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Lọc theo danh mục
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Tìm kiếm theo tên
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', '%' . $keyword . '%');
    }

    /**
     * Lọc theo khoảng giá
     */
    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        
        return $query;
    }

    // ===== CÁC RELATIONSHIP =====

    /**
     * Quan hệ với bảng categories
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Quan hệ với bảng product_images (nhiều ảnh)
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

     /**
     * Quan hệ với bảng cart_item (giỏ hàng)
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Lấy ảnh chính của sản phẩm
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    // ===== CÁC ACCESSOR (Getter) =====

    /**
     * Định dạng giá tiền Việt Nam
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }

    /**
     * Định dạng giá khuyến mãi
     */
    public function getFormattedSalePriceAttribute()
    {
        return $this->sale_price ? number_format($this->sale_price, 0, ',', '.') . 'đ' : null;
    }

    /**
     * Tính phần trăm giảm giá
     */
    public function getDiscountPercentAttribute()
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) {
            return 0;
        }
        
        return round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    /**
     * Lấy giá hiển thị (ưu tiên sale_price)
     */
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?: $this->price;
    }

    /**
     * Kiểm tra có đang giảm giá không
     */
    public function getIsOnSaleAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    /**
     * Lấy URL ảnh chính
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        if ($this->primaryImage) {
            return asset('storage/' . $this->primaryImage->image_path);
        }
        
        return asset('images/no-image.jpg'); // Ảnh mặc định
    }

    /**
     * Trạng thái tồn kho
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity > 10) {
            return 'Còn hàng';
        } elseif ($this->stock_quantity > 0) {
            return 'Sắp hết hàng';
        } else {
            return 'Hết hàng';
        }
    }

    // ===== CÁC MUTATOR (Setter) =====

    /**
     * Tự động tạo slug từ tên sản phẩm
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        
        // Chỉ tạo slug mới nếu chưa có slug hoặc đang tạo mới
        if (!$this->slug || !$this->exists) {
            $this->attributes['slug'] = $this->taoSlugDuyNhat($value);
        }
    }

    /**
     * Đảm bảo SKU luôn là chữ hoa
     */
    public function setSkuAttribute($value)
    {
        $this->attributes['sku'] = $value ? strtoupper($value) : null;
    }

    // ===== CÁC PHƯƠNG THỨC HỖ TRỢ =====

    /**
     * Tạo slug duy nhất
     */
    private function taoSlugDuyNhat($name)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;
        
        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Kiểm tra có thể xóa sản phẩm không
     */
    public function coTheXoa()
    {
        // Thêm logic kiểm tra: có đơn hàng, có trong giỏ hàng, v.v.
        // Ví dụ: return $this->orders()->count() === 0;
        return true;
    }

    /**
     * Cập nhật lượt xem an toàn
     */
    public function tangLuotXem()
    {
        try {
            $this->increment('views');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Kiểm tra tính hợp lệ của dữ liệu trước khi lưu
     */
    public function kiemTraHopLe()
    {
        $errors = [];

        // Kiểm tra tên sản phẩm
        if (empty($this->name)) {
            $errors[] = 'Tên sản phẩm không được để trống';
        }

        // Kiểm tra giá
        if ($this->price <= 0) {
            $errors[] = 'Giá sản phẩm phải lớn hơn 0';
        }

        // Kiểm tra giá khuyến mãi
        if ($this->sale_price && $this->sale_price >= $this->price) {
            $errors[] = 'Giá khuyến mãi phải nhỏ hơn giá gốc';
        }

        // Kiểm tra danh mục
        if ($this->category_id && !Category::find($this->category_id)) {
            $errors[] = 'Danh mục không tồn tại';
        }

        return $errors;
    }

    // ===== EVENT HANDLERS =====

    /**
     * Xử lý trước khi lưu
     */
    protected static function boot()
    {
        parent::boot();

        // Tự động tạo SKU nếu chưa có
        static::creating(function ($product) {
            if (!$product->sku) {
                $product->sku = $product->taoSkuDuyNhat();
            }
        });

        // Kiểm tra dữ liệu trước khi lưu
        static::saving(function ($product) {
            $errors = $product->kiemTraHopLe();
            if (!empty($errors)) {
                throw new \Exception('Dữ liệu không hợp lệ: ' . implode(', ', $errors));
            }
        });
    }

    /**
     * Tạo SKU duy nhất
     */
    private function taoSkuDuyNhat()
    {
        do {
            $sku = 'SP-' . strtoupper(Str::random(8));
        } while (static::where('sku', $sku)->exists());
        
        return $sku;
    }
}
