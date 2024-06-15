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
        $perPage = 12; // Ustaw liczbę elementów na stronę
        $page = request()->get('page', 1); // Pobierz bieżącą stronę

        // Pobieranie ostatnich węzłów z różnych modeli z paginacją
        $properties = Property::with('media') // Eager loading
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate($perPage, ['*'], 'property_page', $page);

        $movableProperties = MovableProperty::with('media') // Eager loading
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate($perPage, ['*'], 'movable_page', $page);

        $claims = Claim::with('media') // Eager loading
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate($perPage, ['*'], 'claim_page', $page);

        $notices = Notice::with('media') // Eager loading
            ->select('id', 'title', 'created', 'slug', 'cena', 'powierzchnia', 'terms')
            ->where('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate($perPage, ['*'], 'notice_page', $page);

        // Dodanie URL do obrazów dla każdego węzła
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
