<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereRaw("search_vector @@ plainto_tsquery('english', ?)", [$search]);
        }

        if ($request->filled('tag')) {
            $tag = $request->tag;
            $query->whereJsonContains('tags', $tag);
        }

        $posts = $query->orderBy('id', 'asc')->get();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['title', 'description']);
        
        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $data['tags'] = [];
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['title', 'description']);

        if ($request->filled('tags')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags));
        } else {
            $data['tags'] = [];
        }

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post moved to trash successfully.');
    }

    public function trash()
    {
        $posts = Post::onlyTrashed()->get();
        return view('posts.trash', compact('posts'));
    }

    public function restore($id)
    {
        Post::withTrashed()->find($id)->restore();
        return redirect()->back()->with('success', 'Post restored successfully!');
    }

    public function forceDelete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->forceDelete();
        return redirect()->back()->with('success', 'Post deleted permanently!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required|string'
        ]);

        $ids = $request->ids;
        $action = $request->action;

        if ($action === 'delete') {
            Post::whereIn('id', $ids)->delete();
            return redirect()->back()->with('success', 'Selected posts moved to trash.');
        }

        if ($action === 'restore') {
            Post::onlyTrashed()->whereIn('id', $ids)->restore();
            return redirect()->back()->with('success', 'Selected posts restored.');
        }

        if ($action === 'force_delete') {
            $posts = Post::onlyTrashed()->whereIn('id', $ids)->get();
            foreach ($posts as $post) {
                if ($post->image) {
                    Storage::disk('public')->delete($post->image);
                }
                $post->forceDelete();
            }
            return redirect()->back()->with('success', 'Selected posts deleted permanently.');
        }

        return redirect()->back();
    }
}