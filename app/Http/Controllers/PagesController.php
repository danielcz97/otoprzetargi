<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Post;
use Carbon\Carbon;

class PagesController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');

        $promotedNodes = Property::select('nieruchomosci.id', 'nieruchomosci.title', 'nieruchomosci.created', 'nieruchomosci.slug', 'nieruchomosci.cena', 'nieruchomosci.powierzchnia', 'nieruchomosci.terms')
            ->join('c144_nodes_premiums', 'nieruchomosci.id', '=', 'c144_nodes_premiums.node_id')
            ->where('c144_nodes_premiums.premium_id', '=', 4)
            ->where('c144_nodes_premiums.datefrom', '<=', $today)
            ->where('c144_nodes_premiums.dateto', '>=', $today)
            ->orderBy('nieruchomosci.created', 'desc')
            ->limit(10)
            ->get();

        $latestNodes = Property::select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->orderBy('created', 'desc')
            ->limit(20)
            ->get();

        $latestPosts = Post::select('id', 'title', 'created', 'slug')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact('promotedNodes', 'latestNodes', 'latestPosts'));
    }
}

