<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() //hien thi danh sach bai viet
    {
        $posts = Post::latest()->get();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('title', 'content', 'category', 'status');

        // Xử lý upload hình ảnh
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        Post::create($data);
        return redirect()->route('admin.create')->with('success', 'Đã thêm bài viết!');
    }


    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('uploads', 'public'); 
            return asset('storage/' . $path); // trả về đường dẫn ảnh
        }
        return response()->json(['error' => 'Không có ảnh'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id); //tim bai viet theo id
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            'status' => 'required|in:published,draft',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = Post::findOrFail($id);

        //cap nhat du lieu moi
        $data = $request->only('title', 'content', 'category', 'status');

        // xu ly upload hinh anh
        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            // Lưu ảnh mới
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        $post->update($data);
        return redirect()->route('admin.index')->with('success', 'Đã cập nhật bài viết!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Xoá ảnh nếu có
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        return redirect()->route('admin.index')->with('success', 'Đã xóa bài viết!');
    }

    /**
     * Hiển thị danh sách bài viết dịch vụ
     */
    public function dichVu()
    {
        $posts = Post::where('category', 'dich-vu')->latest()->get();
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Hiển thị danh sách bài viết quảng cáo
     */
    public function quangCao()
    {
        $posts = Post::where('category', 'quang-cao')->latest()->get();
        return view('admin.posts.index', compact('posts'));
    }
}
