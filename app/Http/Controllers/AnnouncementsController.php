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

        // Pobieranie wszystkich rekordów z różnych modeli
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

        // Połączenie wyników w jedną kolekcję
        $latestNodes = collect()
            ->merge($properties)
            ->merge($movableProperties)
            ->merge($claims)
            ->merge($notices);

        // Dodanie URL do obrazów dla każdego węzła
        $latestNodes->each(function ($node) {
            $node->thumbnail_url = $node->getMediaUrl();
        });

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
