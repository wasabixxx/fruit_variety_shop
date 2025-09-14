<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of published pages
     */
    public function index()
    {
        $pages = Page::published()
                    ->orderBy('title')
                    ->paginate(12);
        
        return view('pages.index', compact('pages'));
    }

    /**
     * Display the specified page
     */
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
                   ->published()
                   ->firstOrFail();
        
        // Determine which template to use
        $template = $this->getTemplate($page->template);
        
        return view($template, compact('page'));
    }

    /**
     * Get template path based on page template
     */
    private function getTemplate($template)
    {
        $templates = [
            'default' => 'pages.show',
            'about' => 'pages.about',
            'contact' => 'pages.contact',
            'policy' => 'pages.policy',
            'full-width' => 'pages.full-width'
        ];

        return $templates[$template] ?? 'pages.show';
    }

    /**
     * Show about us page
     */
    public function about()
    {
        $page = Page::where('slug', 'about-us')
                   ->orWhere('slug', 've-chung-toi')
                   ->published()
                   ->first();

        if (!$page) {
            // Create default about page if not exists
            $page = $this->createDefaultAboutPage();
        }

        return view('pages.about', compact('page'));
    }

    /**
     * Show contact page
     */
    public function contact()
    {
        $page = Page::where('slug', 'contact')
                   ->orWhere('slug', 'lien-he')
                   ->published()
                   ->first();

        if (!$page) {
            // Create default contact page if not exists
            $page = $this->createDefaultContactPage();
        }

        return view('pages.contact', compact('page'));
    }

    /**
     * Show privacy policy page
     */
    public function privacy()
    {
        $page = Page::where('slug', 'privacy-policy')
                   ->orWhere('slug', 'chinh-sach-bao-mat')
                   ->published()
                   ->first();

        if (!$page) {
            // Create default privacy page if not exists
            $page = $this->createDefaultPrivacyPage();
        }

        return view('pages.policy', compact('page'));
    }

    /**
     * Show terms of service page
     */
    public function terms()
    {
        $page = Page::where('slug', 'terms-of-service')
                   ->orWhere('slug', 'dieu-khoan-dich-vu')
                   ->published()
                   ->first();

        if (!$page) {
            // Create default terms page if not exists
            $page = $this->createDefaultTermsPage();
        }

        return view('pages.policy', compact('page'));
    }

    /**
     * Handle contact form submission
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000'
        ]);

        // Here you would typically send email or save to database
        // For now, just return success message
        
        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
    }

    /**
     * Create default pages (fallback)
     */
    private function createDefaultAboutPage()
    {
        return (object) [
            'title' => 'Về Chúng Tôi',
            'content' => '<h1>Về Chúng Tôi</h1><p>Chào mừng bạn đến với Fruit Variety Shop - nơi cung cấp các loại hạt giống trái cây chất lượng cao.</p>',
            'meta_title' => 'Về Chúng Tôi - Fruit Variety Shop',
            'meta_description' => 'Tìm hiểu về Fruit Variety Shop và sứ mệnh cung cấp hạt giống trái cây chất lượng cao.',
            'template' => 'about'
        ];
    }

    private function createDefaultContactPage()
    {
        return (object) [
            'title' => 'Liên Hệ',
            'content' => '<h1>Liên Hệ Với Chúng Tôi</h1><p>Hãy liên hệ với chúng tôi để được tư vấn về các loại hạt giống.</p>',
            'meta_title' => 'Liên Hệ - Fruit Variety Shop',
            'meta_description' => 'Liên hệ với Fruit Variety Shop để được tư vấn về hạt giống trái cây.',
            'template' => 'contact'
        ];
    }

    private function createDefaultPrivacyPage()
    {
        return (object) [
            'title' => 'Chính Sách Bảo Mật',
            'content' => '<h1>Chính Sách Bảo Mật</h1><p>Chúng tôi cam kết bảo vệ thông tin cá nhân của khách hàng.</p>',
            'meta_title' => 'Chính Sách Bảo Mật - Fruit Variety Shop',
            'meta_description' => 'Chính sách bảo mật thông tin khách hàng của Fruit Variety Shop.',
            'template' => 'policy'
        ];
    }

    private function createDefaultTermsPage()
    {
        return (object) [
            'title' => 'Điều Khoản Dịch Vụ',
            'content' => '<h1>Điều Khoản Dịch Vụ</h1><p>Các điều khoản và điều kiện sử dụng dịch vụ.</p>',
            'meta_title' => 'Điều Khoản Dịch Vụ - Fruit Variety Shop',
            'meta_description' => 'Điều khoản và điều kiện sử dụng dịch vụ của Fruit Variety Shop.',
            'template' => 'policy'
        ];
    }
}
