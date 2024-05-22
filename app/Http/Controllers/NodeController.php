<?php

namespace App\Http\Controllers;

use App\Models\Node;
use Illuminate\Http\Request;

class NodeController extends Controller
{
    public function index()
    {
        $nodes = Node::all();
        return view('nodes.index', compact('nodes'));
    }

    public function show($id)
    {
        $node = Node::findOrFail($id);
        return view('nodes.show', compact('node'));
    }
}