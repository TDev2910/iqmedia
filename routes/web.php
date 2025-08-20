<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\PostController as UserPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;


// Trang chủ
Route::get('/', [UserPostController::class, 'index'])->name('home');

// Các trang thông tin
Route::get('/contact', function () {
    return view('pages.contact');
})->name('pages.contact');

Route::get('/about', function () {
    return view('pages.about');
})->name('pages.about');

// ===== ROUTES AUTHENTICATION =====
Route::middleware('guest')->group(function () {
    // Đăng ký
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Đăng nhập
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Đăng xuất (cần auth)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ===== ROUTES BÀI VIẾT (USER) =====

// Chi tiết bài viết
Route::get('/posts/{id}', [UserPostController::class, 'show'])->name('posts.show');

// Dịch vụ và quảng cáo
Route::get('/dich-vu', [UserPostController::class, 'dichVu'])->name('dich-vu');
Route::get('/quang-cao', [UserPostController::class, 'quangCao'])->name('quang-cao');

// Danh mục bài viết
Route::get('/dich-vu/{category}', [UserPostController::class, 'showCategory'])->name('posts.category');
Route::get('/quang-cao/{category}', [UserPostController::class, 'showCategory'])->name('posts.quangcao');

// ===== ROUTES SẢN PHẨM (USER) =====

// Tất cả sản phẩm
Route::get('/collections/all', [ProductController::class, 'index'])->name('products.index');

// Sản phẩm theo danh mục
Route::get('/collections/{category}', [ProductController::class, 'category'])->name('products.category');

// ===== API ROUTES =====
Route::prefix('api')->group(function () {
    // Tìm kiếm sản phẩm
    Route::get('/products/search', [ProductController::class, 'search'])->name('api.products.search');
    
    // Sản phẩm nổi bật
    Route::get('/products/featured', [ProductController::class, 'featured'])->name('api.products.featured');
});

//route admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard admin
    Route::get('/', [PostController::class, 'index'])->name('index');
    
    //quan ly bai viet
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/', [PostController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard admin
    Route::get('/', [PostController::class, 'index'])->name('index');
    
    //quan ly bai viet
    Route::get('/create', [PostController::class, 'create'])->name('create');
    Route::post('/', [PostController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PostController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PostController::class, 'update'])->name('update');
    Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
    Route::get('/show/{id}', [PostController::class, 'show'])->name('show');
    Route::post('/upload-image', [PostController::class, 'uploadImage'])->name('uploadImage');
    Route::get('/dich-vu', [PostController::class, 'dichVu'])->name('dichvu');
    Route::get('/quang-cao', [PostController::class, 'quangCao'])->name('quangcao');
    Route::put('/{id}', [PostController::class, 'update'])->name('update');
    Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
    Route::get('/show/{id}', [PostController::class, 'show'])->name('show');
    Route::post('/upload-image', [PostController::class, 'uploadImage'])->name('uploadImage');
    Route::get('/dich-vu', [PostController::class, 'dichVu'])->name('dichvu');
    Route::get('/quang-cao', [PostController::class, 'quangCao'])->name('quangcao');
    
    //quan ly san pham bang resource
    Route::resource('products', AdminProductController::class)->names([
        'index' => 'products.index',
        'create' => 'products.create',
        'store' => 'products.store',
        'show' => 'products.show',
        'edit' => 'products.edit',
        'update' => 'products.update',
        'destroy' => 'products.destroy',
    ]);
    
    // Xóa ảnh sản phẩm
    Route::delete('products/{product}/images/{image}', [AdminProductController::class, 'deleteImage'])
        ->name('products.delete-image');
});
});

// ===== ROUTE FALLBACK CHO SẢN PHẨM =====
// Đặt cuối cùng để tránh xung đột với các route khác
Route::get('/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart item
// Route::get('/cart-item/{id}',[CartItemController::class,'store']);

Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/cart',[CartController::class,'index'])->name('cart.index');
    Route::post('/cart/add/{id}',[CartController::class,'addProduct'])->name('cart.add-cart-item');
});