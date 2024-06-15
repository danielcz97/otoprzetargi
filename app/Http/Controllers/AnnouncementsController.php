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
            $perPage = 12;
            $page = request()->get('page', 1);

            // Pobieranie wszystkich rekordów z różnych modeli
            Log::debug('Fetching properties');
            $properties = Property::with('media')
                ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->get();

            Log::debug('Fetching movableProperties');
            $movableProperties = MovableProperty::with('media')
                ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->get();

            Log::debug('Fetching claims');
            $claims = Claim::with('media')
                ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->get();

            Log::debug('Fetching notices');
            $notices = Notice::with('media')
                ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
                ->where('created', '<=', $today)
                ->orderBy('created', 'desc')
                ->get();

            // Połączenie wyników w jedną kolekcję
            Log::debug('Merging collections');
            $latestNodes = collect()
                ->merge($properties)
                ->merge($movableProperties)
                ->merge($claims)
                ->merge($notices);

            // Dodanie URL do obrazów dla każdego węzła
            Log::debug('Setting thumbnail URLs');
            $latestNodes->each(function ($node) {
                $node->thumbnail_url = $node->getMediaUrl();
            });

            // Sortowanie połączonej kolekcji
            Log::debug('Sorting collection');
            $latestNodes = $latestNodes->sortByDesc('created')->values();

            // Paginacja ręczna połączonej kolekcji
            Log::debug('Paginating collection');
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
