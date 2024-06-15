<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\MovableProperty;
use App\Models\Claim;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AnnouncementsController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->format('Y-m-d');
        $perPage = 12;
        $page = request()->get('page', 1);

        $properties = Property::with('media')
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->get();

        $movableProperties = MovableProperty::with('media')
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->get();

        $claims = Claim::with('media')
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->get();

        $notices = Notice::with('media')
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->get();

        $properties->each(function ($node) {
            $node->thumbnail_url = $node->getMediaUrl();
        });

        $movableProperties->each(function ($node) {
            $node->thumbnail_url = $node->getMediaUrl();
        });

        $claims->each(function ($node) {
            $node->thumbnail_url = $node->getMediaUrl();
        });

        $notices->each(function ($node) {
            $node->thumbnail_url = $node->getMediaUrl();
        });

        $latestNodes = collect()
            ->merge($properties)
            ->merge($movableProperties)
            ->merge($claims)
            ->merge($notices);

        $latestNodes = $latestNodes->sortByDesc('created')->values();
        $paginatedNodes = $this->paginate($latestNodes, $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);

        return view('nodes.announcements', compact('paginatedNodes'));
    }

    private function paginate(Collection $items, $perPage, $page, $options)
    {
        $offset = ($page * $perPage) - $perPage;
        return new LengthAwarePaginator(
            $items->slice($offset, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }
}
