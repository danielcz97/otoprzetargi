<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\MovableProperty;
use App\Models\Notice;
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
        $promotedNodes->each(function ($node) {
            $node->thumbnail_url = $node->getMediaUrl();
        });

        $latestNodes = collect();
        $models = [Property::class, MovableProperty::class, Notice::class, Claim::class];

        foreach ($models as $model) {
            $nodes = $model::select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->take(50)
                ->get();

            $nodes->each(function ($node) {
                $node->thumbnail_url = $node->getMediaUrl();
            });

            $latestNodes = $latestNodes->concat($nodes);
        }

        $latestNodes = $latestNodes->sortByDesc('created')->take(50);

        $latestPosts = Post::select('id', 'title', 'created', 'slug')
            ->latest()
            ->take(6)
            ->get();
        $latestPosts->each(function ($post) {
            $post->thumbnail_url = $post->getMediaUrl();
        });

        return view('welcome', compact('promotedNodes', 'latestNodes', 'latestPosts'));
    }
}
