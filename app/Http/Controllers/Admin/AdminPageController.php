<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminPageController extends Controller
{
    /**
     * Display a listing of pages
     */
    public function index(Request $request)
    {
        $query = Page::with(['creator', 'updater']);

        // Search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'published':
                    $query->where('is_published', true);
                    break;
                case 'draft':
                    $query->where('is_published', false);
                    break;
                case 'menu':
                    $query->where('show_in_menu', true);
                    break;
            }
        }

        if ($request->filled('template')) {
            $query->where('template', $request->template);
        }

        $pages = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Preserve query parameters in pagination links
        $pages->appends(request()->query());
        
        $totalPages = Page::count();
        $publishedPages = Page::where('is_published', true)->count();
        $draftPages = Page::where('is_published', false)->count();
        $menuPages = Page::where('show_in_menu', true)->count();

        $stats = [
            'total' => $totalPages,
            'published' => $publishedPages,
            'draft' => $draftPages,
            'menu' => $menuPages
        ];

        return view('admin.pages.index', compact('pages', 'stats'));
    }

    /**
     * Show the form for creating a new page
     */
    public function create()
    {
        $templates = Page::getTemplates();
        return view('admin.pages.create', compact('templates'));
    }

    /**
     * Store a newly created page in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'template' => 'required|string|in:' . implode(',', array_keys(Page::getTemplates())),
            'is_published' => 'boolean',
            'show_in_menu' => 'boolean',
            'menu_order' => 'nullable|integer|min:0',
            'featured_image' => 'nullable|url'
        ]);

        $data = $request->all();
        
        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        // Check slug uniqueness again after processing
        $existingPage = Page::where('slug', $data['slug'])->first();
        if ($existingPage) {
            $data['slug'] = $data['slug'] . '-' . time();
        }

        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $page = Page::create($data);

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Trang đã được tạo thành công!');
    }

    /**
     * Display the specified page
     */
    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified page
     */
    public function edit(Page $page)
    {
        $templates = Page::getTemplates();
        return view('admin.pages.edit', compact('page', 'templates'));
    }

    /**
     * Update the specified page in storage
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pages', 'slug')->ignore($page->id)],
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'template' => 'required|string|in:' . implode(',', array_keys(Page::getTemplates())),
            'is_published' => 'boolean',
            'show_in_menu' => 'boolean',
            'menu_order' => 'nullable|integer|min:0',
            'featured_image' => 'nullable|url'
        ]);

        $data = $request->all();
        
        // Process slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        $data['updated_by'] = Auth::id();

        $page->update($data);

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Trang đã được cập nhật thành công!');
    }

    /**
     * Remove the specified page from storage
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Trang đã được xóa thành công!');
    }

    /**
     * Toggle page status
     */
    public function toggleStatus(Page $page)
    {
        $page->update([
            'is_published' => !$page->is_published,
            'updated_by' => Auth::id()
        ]);

        $status = $page->is_published ? 'published' : 'unpublished';
        return back()->with('success', "Trang đã được {$status}!");
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,delete,add_to_menu,remove_from_menu',
            'page_ids' => 'required|array',
            'page_ids.*' => 'exists:pages,id'
        ]);

        $pages = Page::whereIn('id', $request->page_ids);
        $count = $pages->count();

        switch ($request->action) {
            case 'publish':
                $pages->update([
                    'is_published' => true,
                    'updated_by' => Auth::id()
                ]);
                $message = "{$count} trang đã được publish thành công!";
                break;

            case 'unpublish':
                $pages->update([
                    'is_published' => false,
                    'updated_by' => Auth::id()
                ]);
                $message = "{$count} trang đã được unpublish thành công!";
                break;

            case 'add_to_menu':
                $pages->update([
                    'show_in_menu' => true,
                    'updated_by' => Auth::id()
                ]);
                $message = "{$count} trang đã được thêm vào menu!";
                break;

            case 'remove_from_menu':
                $pages->update([
                    'show_in_menu' => false,
                    'updated_by' => Auth::id()
                ]);
                $message = "{$count} trang đã được xóa khỏi menu!";
                break;

            case 'delete':
                $pages->delete();
                $message = "{$count} trang đã được xóa thành công!";
                break;
        }

        return back()->with('success', $message);
    }

    /**
     * Preview page
     */
    public function preview(Page $page)
    {
        // Create a temporary published version for preview
        $previewPage = clone $page;
        $previewPage->is_published = true;
        
        $template = $this->getTemplate($page->template);
        
        return view($template, ['page' => $previewPage, 'preview' => true]);
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
     * Duplicate page
     */
    public function duplicate(Page $page)
    {
        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Copy)';
        $newPage->slug = $page->slug . '-copy-' . time();
        $newPage->is_published = false;
        $newPage->created_by = Auth::id();
        $newPage->updated_by = Auth::id();
        $newPage->published_at = null;
        $newPage->save();

        return redirect()->route('admin.pages.edit', $newPage)
                        ->with('success', 'Trang đã được sao chép thành công!');
    }
}
