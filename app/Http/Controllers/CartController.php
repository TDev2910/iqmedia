<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $cart = Cart::firstOrCreate([
            'user_id' => $userId
        ]);
        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        //
        return view('pages.cart', ['cartItems' => $cartItems]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function addProduct(string $id)
    {
        $userId = Auth::id();

        // Tìm giỏ hàng của user nếu chưa có thì tạo mỗi lần user thêm giỏ hàng
        $cart = Cart::firstOrCreate([
            'user_id' => $userId
        ]);

        // Kiểm tra xem sản phẩm đã có trong cart chưa
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $id) // hoặc $id nếu bạn đặt tên vậy
            ->first();

        if ($cartItem) {
            // Nếu đã có → tăng số lượng lên
            $cartItem->increment('quantity');
        } else {
            // Nếu chưa có → tạo mới cart item
            CartItem::create([
                'cart_id'    => $cart->id,
                'product_id' => $id,
                'quantity'   => 1
            ]);
        }
        return redirect()->route('user.cart.index');
    }
}
