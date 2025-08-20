@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <section class="h-100 h-custom" style="background-color: #d2c9ff;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12">
                    <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                        <div class="card-body p-0">
                            <div class="row g-0">
                                <div class="col-lg-8">
                                    <div class="p-5">
                                        <div class="d-flex justify-content-between align-items-center mb-5">
                                            <h1 class="fw-bold mb-0">Giỏ hàng</h1>
                                            <h6 class="mb-0 text-muted">{{ count($cartItems) }} sản phẩm</h6>
                                        </div>
                                        <hr class="my-4">

                                        {{-- danh sách sản phẩm trong giỏ hàng --}}
                                        @foreach ($cartItems as $cartItem)
                                            <div class="row mb-4 d-flex justify-content-between align-items-center">
                                                <div class="col-md-2 col-lg-2 col-xl-2">
                                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img5.webp"
                                                        class="img-fluid rounded-3" alt="Cotton T-shirt">
                                                </div>
                                                <div class="col-md-3 col-lg-3 col-xl-3 flex-grow-1 flow"
                                                    style="--flow-spacer:1em">
                                                    <h6 class="text-muted">{{ $cartItem->product->name }}</h6>
                                                    <h6 class="mb-0">{{ $cartItem->product->short_description }}</h6>
                                                </div>
                                                <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                        <svg class="icon">
                                                            <use xlink:href="{{ asset('img/picture_service/svg-icons/utility-icons.svg#minus') }}"></use>
                                                        </svg>
                                                    </button>

                                                    <input min="1" name="quantity" value="{{ $cartItem->quantity }}" type="number"
                                                        class="cart-item-quantity form-control form-control-sm" />

                                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-link px-2"
                                                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                        <svg class="icon">
                                                            <use xlink:href="{{ asset('img/picture_service/svg-icons/utility-icons.svg#plus') }}"></use>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                    <h6 class="mb-0">{{ $cartItem->product->price }}</h6>
                                                </div>
                                                <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                    <a href="#!" class="text-muted"><i class="fas fa-times"></i></a>
                                                </div>
                                            </div>
                                            <hr class="my-4">
                                        @endforeach

                                        <div class="pt-5">
                                            <h6 class="mb-0"><a href="{{ route('products.index') }}" class="text-body"><i
                                                        class="fas fa-long-arrow-alt-left me-2"></i>Back to shop</a></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 bg-body-tertiary">
                                    <div class="p-5">
                                        <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                                        <hr class="my-4">

                                        <div class="d-flex justify-content-between mb-4">
                                            <h5 class="text-uppercase">items 3</h5>
                                            <h5>€ 132.00</h5>
                                        </div>

                                        <h5 class="text-uppercase mb-3">Shipping</h5>

                                        <div class="mb-4 pb-2">
                                            <select data-mdb-select-init>
                                                <option value="1">Standard-Delivery- €5.00</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                                <option value="4">Four</option>
                                            </select>
                                        </div>

                                        <h5 class="text-uppercase mb-3">Give code</h5>

                                        <div class="mb-5">
                                            <div data-mdb-input-init class="form-outline">
                                                <input type="text" id="form3Examplea2"
                                                    class="form-control form-control-lg" />
                                                <label class="form-label" for="form3Examplea2">Enter your code</label>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <div class="d-flex justify-content-between mb-5">
                                            <h5 class="text-uppercase">Total price</h5>
                                            <h5>€ 137.00</h5>
                                        </div>

                                        <button type="button" data-mdb-button-init data-mdb-ripple-init
                                            class="btn btn-dark btn-block btn-lg"
                                            data-mdb-ripple-color="dark">Check out</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection