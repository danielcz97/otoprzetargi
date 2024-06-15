<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\MovableProperty;
use App\Models\Claim;
use App\Models\Notice;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AnnouncementsController extends Controller
{
    public function index()
    {
        Log::debug('AnnouncementsController@index started');

        try {
            $today = Carbon::now()->format('Y-m-d');
            $perPage = 10; // Ustaw liczbę elementów na stronę
            $page = request()->get('page', 1); // Pobierz bieżącą stronę

            Log::debug('Fetching properties');
            $properties = Property::with('media') // Eager loading
                ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->paginate($perPage, ['*'], 'property_page', $page);

            Log::debug('Fetching movableProperties');
            $movableProperties = MovableProperty::with('media') // Eager loading
                ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->paginate($perPage, ['*'], 'movable_page', $page);

            Log::debug('Fetching claims');
            $claims = Claim::with('media') // Eager loading
                ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->paginate($perPage, ['*'], 'claim_page', $page);

            Log::debug('Fetching notices');
            $notices = Notice::with('media') // Eager loading
                ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->paginate($perPage, ['*'], 'notice_page', $page);

            // Dodanie URL do obrazów dla każdego węzła
            Log::debug('Setting thumbnail URLs for properties');
            $properties->each(function ($node) {
                $node->thumbnail_url = $node->getMediaUrl();
            });

            Log::debug('Setting thumbnail URLs for movableProperties');
            $movableProperties->each(function ($node) {
                $node->thumbnail_url = $node->getMediaUrl();
            });

            Log::debug('Setting thumbnail URLs for claims');
            $claims->each(function ($node) {
                $node->thumbnail_url = $node->getMediaUrl();
            });

            Log::debug('Setting thumbnail URLs for notices');
            $notices->each(function ($node) {
                $node->thumbnail_url = $node->getMediaUrl();
            });

            // Połączenie wyników w jedną kolekcję
            $latestNodes = collect()
                ->merge($properties->items())
                ->merge($movableProperties->items())
                ->merge($claims->items())
                ->merge($notices->items());

            // Sortowanie połączonej kolekcji
            $latestNodes = $latestNodes->sortByDesc('created')->values();

            // Paginacja ręczna połączonej kolekcji
            $paginatedNodes = $this->paginate($latestNodes, $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);

            Log::debug('Rendering view with paginated nodes');
            return view('nodes.announcements', compact('paginatedNodes'));
        } catch (\Exception $e) {
            Log::error('Error in AnnouncementsController@index: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
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
