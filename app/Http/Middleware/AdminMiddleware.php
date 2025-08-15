<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Xử lý request đến - kiểm tra quyền admin
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra người dùng đã đăng nhập và có role admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }     
        
        // Nếu chưa đăng nhập, chuyển đến trang login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để truy cập khu vực quản trị.')
                ->with('intended', $request->url()); // Lưu URL để redirect sau khi login
        }  
        
        // Nếu đã đăng nhập nhưng không phải admin
        return redirect()->route('home')
            ->with('error', 'Bạn không có quyền truy cập vào khu vực quản trị.');
    }
}
