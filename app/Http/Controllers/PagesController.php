<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PagesController extends Controller
{
    public function index()
    {
        // Włączanie logowania zapytań
        DB::enableQueryLog();

        // Mierzenie czasu zapytań
        $start = microtime(true);
        $promotedNodes = Cache::remember('promoted_nodes', 60, function () {
            return Property::select('id', 'title', 'created', 'slug') // Pobieranie tylko potrzebnych kolumn
                ->orderBy('created', 'desc')
                ->limit(10)
                ->get();
        });
        $timePromotedNodes = microtime(true) - $start;

        $start = microtime(true);
        $latestNodes = Cache::remember('latest_nodes', 60, function () {
            return Property::select('id', 'title', 'created', 'slug') // Pobieranie tylko potrzebnych kolumn
                ->orderBy('created', 'desc')
                ->limit(20)
                ->get();
        });
        $timeLatestNodes = microtime(true) - $start;

        $start = microtime(true);
        $latestPosts = Cache::remember('latest_posts', 60, function () {
            return Post::select('id', 'title', 'created', 'slug') // Pobieranie tylko potrzebnych kolumn
                ->latest()
                ->take(6)
                ->get();
        });
        $timeLatestPosts = microtime(true) - $start;

        // Logowanie zapytań i czasu ich wykonania
        $queries = DB::getQueryLog();
        foreach ($queries as $query) {
            Log::info('Query Time: ' . $query['time'] . ' ms', ['query' => $query['query'], 'bindings' => $query['bindings']]);
        }
        Log::info('Time for promotedNodes query: ' . $timePromotedNodes . ' seconds');
        Log::info('Time for latestNodes query: ' . $timeLatestNodes . ' seconds');
        Log::info('Time for latestPosts query: ' . $timeLatestPosts . ' seconds');

        return view('welcome', compact('promotedNodes', 'latestNodes', 'latestPosts'));
    }
}

