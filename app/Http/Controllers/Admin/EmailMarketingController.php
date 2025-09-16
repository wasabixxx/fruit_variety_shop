<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use App\Models\Voucher;
use App\Models\User;
use App\Mail\PromotionMail;
use Illuminate\Support\Facades\Mail;

class EmailMarketingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                return redirect()->route('home')->with('error', 'Không có quyền truy cập.');
            }
            return $next($request);
        });
    }

    /**
     * Display the email marketing dashboard
     */
    public function index()
    {
        $subscribersCount = NewsletterSubscriber::active()->count();
        $usersCount = User::where('role', 'user')->count();
        $totalSubscribers = $subscribersCount + $usersCount;
        
        $recentSubscribers = NewsletterSubscriber::active()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.email-marketing.index', compact(
            'subscribersCount', 
            'usersCount', 
            'totalSubscribers', 
            'recentSubscribers'
        ));
    }

    /**
     * Show form to create promotion email
     */
    public function createPromotion()
    {
        $vouchers = Voucher::where('is_active', true)
            ->where('expires_at', '>', now())
            ->get();

        return view('admin.email-marketing.create-promotion', compact('vouchers'));
    }

    /**
     * Send promotion email
     */
    public function sendPromotion(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'send_to' => 'required|in:all,subscribers,users'
        ]);

        $voucher = Voucher::findOrFail($request->voucher_id);
        $recipients = $this->getRecipients($request->send_to);

        $sentCount = 0;
        $failedCount = 0;

        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send(
                    new PromotionMail($voucher, $request->subject, $request->content)
                );
                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error('Failed to send promotion email to ' . $email . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.email-marketing.index')
            ->with('success', "Đã gửi thành công {$sentCount} email. Thất bại: {$failedCount}");
    }

    /**
     * Show newsletter subscribers list
     */
    public function subscribers()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.email-marketing.subscribers', compact('subscribers'));
    }

    /**
     * Get recipients based on send_to option
     */
    private function getRecipients($sendTo)
    {
        switch ($sendTo) {
            case 'subscribers':
                return NewsletterSubscriber::active()->pluck('email')->toArray();
            case 'users':
                return User::where('role', 'user')->pluck('email')->toArray();
            case 'all':
            default:
                $subscribers = NewsletterSubscriber::active()->pluck('email')->toArray();
                $users = User::where('role', 'user')->pluck('email')->toArray();
                return array_unique(array_merge($subscribers, $users));
        }
    }
}
