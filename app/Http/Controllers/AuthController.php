<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\EmailVerification;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Chào mừng Admin đã đăng nhập thành công!');
            }
            
            return redirect()->intended('/')->with('success', 'Đăng nhập thành công! Chào mừng bạn đến với Fruit Variety Shop.');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác. Vui lòng kiểm tra lại email và mật khẩu.',
        ])->withInput($request->only('email'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Show email verification notice
     */
    public function showVerificationNotice()
    {
        if (auth()->check() && auth()->user()->hasVerifiedEmail()) {
            return redirect('/');
        }
        
        return view('auth.verify-email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        // Generate verification token and send email
        $token = $user->generateEmailVerificationToken();
        
        try {
            Mail::to($user->email)->send(new EmailVerification($user, $token));
            
            return redirect()->route('login')->with('success', 
                'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản của bạn.'
            );
        } catch (\Exception $e) {
            // If email fails, still allow login but show warning
            Auth::login($user);
            return redirect('/')->with('warning', 
                'Đăng ký thành công! Tuy nhiên không thể gửi email xác nhận. Vui lòng liên hệ hỗ trợ.'
            );
        }
    }

    public function logout(Request $request)
    {
        $userName = Auth::user()->name ?? 'Người dùng';
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', "Tạm biệt {$userName}! Bạn đã đăng xuất thành công.");
    }

    /**
     * Verify email with token
     */
    public function verifyEmail(Request $request, $token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 
                'Token xác nhận không hợp lệ hoặc đã hết hạn. Vui lòng đăng ký lại.'
            );
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('info', 
                'Email đã được xác nhận trước đó. Bạn có thể đăng nhập ngay.'
            );
        }

        // Mark email as verified
        $user->markEmailAsVerified();

        return redirect()->route('login')->with('success', 
            'Xác nhận email thành công! Bây giờ bạn có thể đăng nhập vào tài khoản.'
        );
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return back()->with('info', 'Email đã được xác nhận trước đó.');
        }

        $token = $user->generateEmailVerificationToken();
        
        try {
            Mail::to($user->email)->send(new EmailVerification($user, $token));
            return back()->with('success', 'Email xác nhận đã được gửi lại!');
        } catch (\Exception $e) {
            return back()->with('error', 'Không thể gửi email xác nhận. Vui lòng thử lại sau.');
        }
    }
}
