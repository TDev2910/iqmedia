<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra người dùng đã đăng nhập và có role admin
        if (Auth::check() && Auth::user()->role === 'user') {
            return $next($request);
        }

        // Nếu chưa đăng nhập, chuyển đến trang login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để truy cập khu vực quản trị.')
                ->with('intended', $request->url()); // Lưu URL để redirect sau khi login
        }

        return redirect()->route('home')
            ->with('error', 'Bạn cần có tài khoản để có quyền truy cập.');
    }
}
