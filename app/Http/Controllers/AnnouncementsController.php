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

        // Fetch paginated results from each model
        $properties = Property::with('media')
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate($perPage * 4, ['*'], 'property_page', $page);

        $movableProperties = MovableProperty::with('media')
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate($perPage * 4, ['*'], 'movable_page', $page);

        $claims = Claim::with('media')
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate($perPage * 4, ['*'], 'claim_page', $page);

        $notices = Notice::with('media')
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate($perPage * 4, ['*'], 'notice_page', $page);

        // Merge and sort results
        $latestNodes = collect()
            ->merge($properties->items())
            ->merge($movableProperties->items())
            ->merge($claims->items())
            ->merge($notices->items())
            ->sortByDesc('created')
            ->values();

        // Add thumbnail URL for each node
        $latestNodes->each(function ($node) {
            $node->thumbnail_url = $node->getMediaUrl();
        });

        // Paginate merged results
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
