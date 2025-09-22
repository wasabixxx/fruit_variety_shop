<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Skip verification check for admin users
            if ($user->isAdmin()) {
                return $next($request);
            }
            
            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('email.verification.notice')
                    ->with('warning', 'Vui lòng xác nhận email để tiếp tục sử dụng dịch vụ.');
            }
        }
        
        return $next($request);
    }
}
