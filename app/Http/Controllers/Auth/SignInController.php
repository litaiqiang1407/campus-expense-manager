<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SignInController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Signin/Index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    
        // Kiểm tra xem email đã tồn tại trong cơ sở dữ liệu chưa
        $user = \App\Models\User::where('email', $request->email)->first();
    
        if (!$user) {
            // Nếu email không tồn tại, trả về thông báo yêu cầu đăng ký
            return response()->json(['message' => 'Email does not exist. Please register.'], 401);
        }
    
        // Nếu email tồn tại, kiểm tra mật khẩu
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('Home');
        }
    
        // Gọi phương thức trả về thông báo lỗi mật khẩu
        return $this->passwordErrorResponse();
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            return response()->json(['exists' => true]);
        }

        return response()->json(['exists' => false], 404);
    }

    // Phương thức mới để trả về thông báo lỗi mật khẩu
    public function passwordErrorResponse()
    {
        return response()->json(['message' => 'Invalid password.'], 401);
    }
}

