<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Loa BF Audio',
                'slug' => 'loa-bf-audio',
                'description' => 'Các sản phẩm loa chất lượng cao từ BF Audio',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Karaoke gia đình',
                'slug' => 'karaoke-gia-dinh',
                'description' => 'Hệ thống karaoke cho gia đình',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Amply liền Mixer',
                'slug' => 'amply-lien-mixer',
                'description' => 'Amply tích hợp mixer chuyên nghiệp',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Micro BF Audio',
                'slug' => 'micro-bf-audio',
                'description' => 'Micro không dây chất lượng cao',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Power Ampli',
                'slug' => 'power-ampli',
                'description' => 'Cục đẩy công suất lớn',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Trọn bộ karaoke',
                'slug' => 'tron-bo-karaoke',
                'description' => 'Bộ karaoke đầy đủ phụ kiện',
                'is_active' => true,
                'sort_order' => 6
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $products = [
            [
                'name' => 'BFAUDIO J11 KIMCUONG (GOLD)',
                'slug' => 'bfaudio-j11-kimcuong-gold',
                'description' => 'Micro không dây cao cấp BFAUDIO J11 KIMCUONG màu vàng gold, chất lượng âm thanh tuyệt vời',
                'short_description' => 'Micro không dây cao cấp màu vàng gold',
                'price' => 4200000,
                'sale_price' => null,
                'sku' => 'BFA-J11-GOLD',
                'stock_quantity' => 50,
                'category_id' => 4, // Micro BF Audio
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'BFAUDIO J11 KIMCUONG (BLACK)',
                'slug' => 'bfaudio-j11-kimcuong-black',
                'description' => 'Micro không dây cao cấp BFAUDIO J11 KIMCUONG màu đen, thiết kế sang trọng',
                'short_description' => 'Micro không dây cao cấp màu đen',
                'price' => 11560000,
                'sale_price' => null,
                'sku' => 'BFA-J11-BLACK',
                'stock_quantity' => 30,
                'category_id' => 4, // Micro BF Audio
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'BFAUDIO J11',
                'slug' => 'bfaudio-j11',
                'description' => 'Micro không dây BFAUDIO J11 phiên bản tiêu chuẩn, chất lượng ổn định',
                'short_description' => 'Micro không dây phiên bản tiêu chuẩn',
                'price' => 6800000,
                'sale_price' => null,
                'sku' => 'BFA-J11-STD',
                'stock_quantity' => 40,
                'category_id' => 4, // Micro BF Audio
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'BFAUDIO J10',
                'slug' => 'bfaudio-j10',
                'description' => 'Micro không dây BFAUDIO J10, phù hợp cho karaoke gia đình',
                'short_description' => 'Micro không dây cho karaoke gia đình',
                'price' => 6500000,
                'sale_price' => null,
                'sku' => 'BFA-J10',
                'stock_quantity' => 35,
                'category_id' => 4, // Micro BF Audio
                'status' => 'active',
                'is_featured' => false,
            ],
            [
                'name' => 'BF Audio 3W-8.4',
                'slug' => 'bf-audio-3w-8-4',
                'description' => 'Loa BF Audio 3W-8.4 chất lượng cao, âm thanh trong trẻo',
                'short_description' => 'Loa BF Audio chất lượng cao',
                'price' => 7500000,
                'sale_price' => null,
                'sku' => 'BFA-3W84',
                'stock_quantity' => 25,
                'category_id' => 1, // Loa BF Audio
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'BFAUDIOPRO UK-112SA (112SA PRO)',
                'slug' => 'bfaudiopro-uk-112sa',
                'description' => 'Loa chuyên nghiệp BFAUDIOPRO UK-112SA với công suất lớn',
                'short_description' => 'Loa chuyên nghiệp công suất lớn',
                'price' => 8000000,
                'sale_price' => null,
                'sku' => 'BFAPRO-UK112SA',
                'stock_quantity' => 20,
                'category_id' => 1, // Loa BF Audio
                'status' => 'active',
                'is_featured' => false,
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}
