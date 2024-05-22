<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        return view('nodes.index', compact('nodes'));
    }
}