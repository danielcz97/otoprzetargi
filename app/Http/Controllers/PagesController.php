<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Post;

class PagesController extends Controller
{
    public function index()
    {
        $promotedNodes = Property::where('promote', 1)->orderBy('created', 'desc')->limit(10)->get();
        $latestNodes = Property::orderBy('created', 'desc')->limit(20)->get();
        $latestPosts = Post::latest()->take(6)->get();

        return view('welcome', compact('promotedNodes', 'latestNodes', 'latestPosts'));
    }
}