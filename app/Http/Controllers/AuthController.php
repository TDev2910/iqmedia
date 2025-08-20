<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('account.register');
    }

    /**
     * Xử lý đăng ký người dùng mới
     */
    public function register(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'last_name'  => ['required', 'string', 'max:50', 'regex:/^[\p{L}\s]+$/u'],
            'email'      => ['required', 'email:rfc,dns', 'unique:users,email', 'max:255'],
            'phone'      => ['required', 'string', 'regex:/^[0-9+\-\s()]+$/', 'max:20'],
            'password'   => [
                'required', 
                'confirmed', 
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
        ], [
            // Thông báo lỗi tùy chỉnh
            'first_name.required' => 'Vui lòng nhập họ',
            'first_name.regex' => 'Họ chỉ được chứa chữ cái và khoảng trắng',
            'last_name.required' => 'Vui lòng nhập tên',
            'last_name.regex' => 'Tên chỉ được chứa chữ cái và khoảng trắng',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email này đã được đăng ký',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        try {
            // Tạo người dùng mới
            $user = User::create([
                'first_name' => trim($validated['first_name']),
                'last_name'  => trim($validated['last_name']),
                'email'      => strtolower(trim($validated['email'])),
                'phone'      => $this->chuanHoaSoDienThoai($validated['phone']),
                'password'   => Hash::make($validated['password']),
                'role'       => 'user', // Mặc định là user khi đăng ký
            ]);

            // Đăng nhập tự động sau khi đăng ký thành công
            Auth::login($user);

            // Chuyển hướng dựa vào role
            return $this->chuyenHuongTheoRole($user);
            
        } catch (\Exception $e) {
            
            return back()
                ->withInput($request->only('first_name', 'last_name', 'email', 'phone'))
                ->withErrors(['error' => 'Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.']);
        }
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('account.login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function login(Request $request)
    {
        // Validate dữ liệu
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'remember' => ['sometimes', 'boolean'],
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
        ]);

        // Chuẩn hóa email
        $email = strtolower(trim($validated['email']));
        $remember = $request->boolean('remember');

        // Thử đăng nhập với thông tin được cung cấp
        if (Auth::attempt(['email' => $email, 'password' => $validated['password']], $remember)) {
            $user = Auth::user();
            
            // Regenerate session để tránh session fixation
            $request->session()->regenerate();
            
            // Chuyển hướng dựa vào role
            return $this->chuyenHuongTheoRole($user);
        }

        // Đăng nhập thất bại
        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Thông tin đăng nhập không chính xác.',
            ]);
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate session và regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đã đăng xuất thành công');
    }

    // ===== CÁC PHƯƠNG THỨC HỖ TRỢ PRIVATE =====

    /**
     * Chuyển hướng người dùng dựa vào role
     */
    private function chuyenHuongTheoRole($user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.index')
                ->with('success', 'Chào mừng Admin ' . $user->first_name . ' ' . $user->last_name . '!');
        }

        return redirect()->route('home')
            ->with('success', 'Đăng nhập thành công! Chào mừng ' . $user->first_name . '!');
    }

    /**
     * Chuẩn hóa số điện thoại
     */
    private function chuanHoaSoDienThoai($phone)
    {
        // Loại bỏ khoảng trắng và ký tự đặc biệt
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Chuẩn hóa số điện thoại Việt Nam
        if (str_starts_with($phone, '0')) {
            $phone = '+84' . substr($phone, 1);
        } elseif (str_starts_with($phone, '84') && !str_starts_with($phone, '+84')) {
            $phone = '+' . $phone;
        } elseif (!str_starts_with($phone, '+')) {
            $phone = '+84' . $phone;
        }

        return $phone;
    }
}
