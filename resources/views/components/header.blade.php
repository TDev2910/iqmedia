<header>
    {{-- Primary Header: Logo, Search, Hotline, User, Cart --}}
    <div class="primary-header">
        <div class="custom-container">
            <div class="nav-wrapper">
                {{-- Logo --}}
                <a class="header_logo">
                    <img src="{{ asset('img/picture_service/logo/logo.png') }}" class="logo" alt="IQ Media Logo" />
                </a>
                {{-- Search --}}
                <div class="header_search">
                    <form class="header_search-bar">
                        <input type="text" id="search-bar" style="" placeholder="Tìm kiếm..."/>
                        <button type="submit" class="button">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                class="bi bi-search | icon"
                                data-size="big"
                                viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                            </svg>
                        </button>
                    </form> 
                </div>

                {{-- Hotline, User, Cart --}}
                <div class="header_buttons">
                    {{-- Hotline --}}
                    <button class="header_hotline">
                        <div class="header_hotline-icon">
                            <i class="bi bi-chat-left-text | icon | me-200"></i>
                        </div>
                        <div class="header_hotline-infor">
                            <span class="text-left">Hotline</span>
                            <a class="header_hotline-number">09333 4 8885</a>
                        </div>
                    </button>

                    {{-- Mobile toggle nav  --}}
                    <button class="mobile-toggle-nav | button" data-shape="circle" data-controls="primary-nav">
                        <svg data-size="big" xmlns="http://www.w3.org/2000/svg" class="bi bi-list icon icon-menu"
                            viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                        </svg>
                        <span class="visually-hidden">Menu</span>
                    </button>

                    {{-- User Dropdown --}}
                    @php
                        $user = Auth::user();
                    @endphp
                    <div class="dropdown">
                        <a class="header_user | button" type="button" id="userDropdown" data-shape="circle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <svg data-size="big" xmlns="http://www.w3.org/2000/svg" class="bi bi-person-fill | icon"
                                data-size="big" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown"
                            style="min-width: 250px;">
                            @if(Auth::check())
                                {{-- Đã đăng nhập: Hiển thị thông tin user --}}
                                <li class="px-3 py-2 text-center">
                                    <div class="fw-bold">
                                        {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ $user->email }}
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-gear me-2"></i>
                                        Account Settings
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right me-2"></i>
                                            Log out
                                        </button>
                                    </form>
                                </li>
                            @else
                                {{-- Chưa đăng nhập: Hiển thị nút đăng nhập --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        Đăng nhập
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    {{-- Cart --}}
                    <a href="{{ route('user.cart.index') }}" class="button" data-shape="circle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-cart | icon" data-size="big"
                            viewBox="0 0 16 16">
                            <path
                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Primary nav --}}
    <nav class="primary-nav">
        <div class="custom-container">
            <ul     class="nav-list" role="list" aria-label="primary">
                <li><a href="/">Trang chủ</a></li>
                <li><a href="{{ route('pages.about') }}">Giới thiệu</a></li>
                <li class="has-submenu" data-controls="submenu">
                    <a href="">
                        Dịch vụ
                        <i class="bi bi-chevron-down | icon" data-type="inverted" data-size="small"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('posts.category', 'cho-thue-man-hinh-anh-sang') }}">Cho thuê màn hinh ánhsáng</a></li>
                        <li><a href="{{ route('posts.category', 'cho-thue-man-hinh-led') }}">Cho thuê màn hình LED</a> </li>
                        <li><a href="{{ route('posts.category', 'dich-vu-thuong-mai') }}">Dịch vụ thương mại</a></li>
                        <li><a href="{{ route('posts.category', 'thi-cong-backdrop') }}">Thi công PhotoBooth và Backdrop</a></li>
                        <li><a href="{{ route('posts.category', 'to-chuc-su-kien') }}">Tổ chức sự kiện</a></li>
                        <li><a href="{{ route('posts.category', 'quay-phim-chup-hinh') }}">Quay phim - Chụp hình sựkiện chuyên nghiệp tại IQ Media</a></li>
                    </ul>
                </li>
                <li class="has-submenu" data-controls="submenu">
                    <a href=""> 
                        Quảng cáo
                        <i class="bi bi-chevron-down | icon" data-type="inverted" data-size="small"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('posts.category', 'gia-cong-cnc-laser') }}">Gia Công CNC - LASER</a> </li>
                        <li><a href="{{ route('posts.category', 'thi-cong-quang-cao') }}">Thi Công Quảng Cáo</a></li>
                        <li><a href="{{ route('posts.category', 'in-an-ky-thuat-so') }}">In Ấn Kỹ Thuật Số</a></li>
                        <li><a href="{{ route('posts.category', 'thiet-ke-quang-cao') }}">Thiết Kế Quảng Cáo</a></li>        
                    </ul>
                </li>
                <li class="has-submenu" data-controls="submenu">
                    <a href="{{ route('products.index') }}">
                        Thương mại
                        <i class="bi bi-chevron-down | icon" data-type="inverted" data-size="small"></i>
                    </a>
                    <ul class="submenu">
                        <li><a href="{{ route('products.category', 'loa-bf-audio') }}">Loa BF Audio</a></li>
                        <li><a href="{{ route('products.category', 'karaoke-gia-dinh') }}">Karaoke gia đình</a></li>
                        <li><a href="{{ route('products.category', 'bo-quan-ly-nguon') }}">Bộ quản lý nguồn</a></li>
                        <li><a href="{{ route('products.category', 'amply-lien-mixer') }}">Amply liền Mixer</a></li>
                        <li><a href="{{ route('products.category', 'loa-boutum') }}">Loa Boutum</a></li>
                        <li><a href="{{ route('products.category', 'loa-laptop-usa') }}">Loa Laptop (USA)</a></li>
                        <li><a href="{{ route('products.category', 'micro-bf-audio') }}">Micro BF Audio</a></li>
                        <li><a href="{{ route('products.category', 'mixer-bf-digital-karaoke') }}">Mixer BF Digital Karaoke</a></li>
                        <li><a href="{{ route('products.category', 'power-ampli') }}">Power Ampli</a></li>
                        <li><a href="{{ route('products.category', 'tron-bo-karaoke') }}">Trọn bộ karaoke</a></li>
                    </ul>
                </li>
                <li><a href="/tin-tuc">Tin tức</a></li>
                <li><a href="#">Chia sẻ kinh nghiệm</a></li>
                <li><a href="{{ route('pages.contact') }}">Liên hệ</a></li>
            </ul>
        </div>
    </nav>
</header>
