<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(15);
        $posts->each(function ($post) {
            $post->mainMediaUrl = $post->getMediaUrl();
        });
        return view('post.index', compact('posts'));
    }

    public function view($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $mainMediaUrl = $post->getMediaUrl();

        return view('post.view', compact('post', 'mainMediaUrl'));
    }
}