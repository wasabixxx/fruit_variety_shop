<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterSubscriber;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:newsletter_subscribers,email',
            'name' => 'nullable|string|max:255'
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được đăng ký nhận tin.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ]);
            }
            return back()->withErrors($validator)->withInput();
        }

        try {
            NewsletterSubscriber::create([
                'email' => $request->email,
                'name' => $request->name,
            ]);

            $message = 'Đăng ký nhận tin thành công! Cảm ơn bạn đã quan tâm.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            $message = 'Có lỗi xảy ra, vui lòng thử lại.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ]);
            }
            
            return back()->with('error', $message);
        }
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request, $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return view('newsletter.unsubscribe', [
                'success' => false,
                'message' => 'Link hủy đăng ký không hợp lệ.'
            ]);
        }

        if (!$subscriber->is_active) {
            return view('newsletter.unsubscribe', [
                'success' => false,
                'message' => 'Email này đã được hủy đăng ký trước đó.'
            ]);
        }

        $subscriber->unsubscribe();

        return view('newsletter.unsubscribe', [
            'success' => true,
            'message' => 'Hủy đăng ký nhận tin thành công.',
            'email' => $subscriber->email
        ]);
    }

    /**
     * Resubscribe to newsletter
     */
    public function resubscribe(Request $request, $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            return redirect()->route('home')->with('error', 'Link không hợp lệ.');
        }

        $subscriber->resubscribe();

        return redirect()->route('home')->with('success', 'Đã đăng ký lại nhận tin thành công!');
    }
}
