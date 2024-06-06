<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Comunicats;
use App\Models\Property;
use App\Models\MovableProperty;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    public function propertiesList(Request $request)
    {
        $query = Property::query();

        $query->join('c144_nodes_teryts', 'nieruchomosci.id', '=', 'c144_nodes_teryts.node_id')
            ->select('nieruchomosci.*', 'c144_nodes_teryts.latitude', 'c144_nodes_teryts.longitude', 'c144_nodes_teryts.miasto');

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = $request->input('radius', 0);

            if ($radius > 0) {
                $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(c144_nodes_teryts.latitude)) * cos(radians(c144_nodes_teryts.longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(c144_nodes_teryts.latitude))))";

                $query->whereRaw("$haversine <= ?", [$radius]);
            } else {
                if ($request->filled('city')) {
                    $city = $request->input('city');
                    $query->where('c144_nodes_teryts.miasto', 'like', '%' . $city . '%');
                }
            }
        }

        if ($request->filled('cena_od')) {
            $query->where('cena', '>=', $request->input('cena_od'));
        }
        if ($request->filled('cena_do')) {
            $query->where('cena', '<=', $request->input('cena_do'));
        }

        if ($request->filled('powierzchnia_od')) {
            $query->where('powierzchnia', '>=', $request->input('powierzchnia_od'));
        }
        if ($request->filled('powierzchnia_do')) {
            $query->where('powierzchnia', '<=', $request->input('powierzchnia_do'));
        }

        if ($request->filled('transaction_type') && $request->input('transaction_type') !== '') {
            $transactionType = $request->input('transaction_type');
            $query->where('terms', 'like', '%"' . $transactionType . '"%');
        }

        if ($request->filled('subject') && $request->input('subject') !== '') {
            $subject = $request->input('subject');
            $query->where('terms', 'like', '%"' . $subject . '"%');
        }
        $today = Carbon::today();

        $query = $query->whereDate('created', '<=', $today);
        $properties = $query->orderBy('created', 'desc')->paginate(15);
        foreach ($properties as $property) {
            $media = $property->getFirstMedia('default');
            $property->thumbnail_url = $media ? $media->getUrl() : null;
        }
        return view('nodes.nieruchomosci', compact('properties'));
    }


    public function ruchomosci(Request $request)
    {
        $query = MovableProperty::query();

        $query->join('c144_nodes_teryts', 'ruchomosci.id', '=', 'c144_nodes_teryts.node_id')
            ->select('ruchomosci.*', 'c144_nodes_teryts.latitude', 'c144_nodes_teryts.longitude', 'c144_nodes_teryts.miasto');

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = $request->input('radius', 0);

            if ($radius > 0) {
                $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(c144_nodes_teryts.latitude)) * cos(radians(c144_nodes_teryts.longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(c144_nodes_teryts.latitude))))";

                $query->whereRaw("$haversine <= ?", [$radius]);
            } else {
                if ($request->filled('city')) {
                    $city = $request->input('city');
                    $query->where('c144_nodes_teryts.miasto', 'like', '%' . $city . '%');
                }
            }
        }

        if ($request->filled('cena_od')) {
            $query->where('cena', '>=', $request->input('cena_od'));
        }
        if ($request->filled('cena_do')) {
            $query->where('cena', '<=', $request->input('cena_do'));
        }

        if ($request->filled('powierzchnia_od')) {
            $query->where('powierzchnia', '>=', $request->input('powierzchnia_od'));
        }
        if ($request->filled('powierzchnia_do')) {
            $query->where('powierzchnia', '<=', $request->input('powierzchnia_do'));
        }

        if ($request->filled('transaction_type') && $request->input('transaction_type') !== '') {
            $transactionType = $request->input('transaction_type');
            $query->where('terms', 'like', '%"' . $transactionType . '"%');
        }

        if ($request->filled('subject') && $request->input('subject') !== '') {
            $subject = $request->input('subject');
            $query->where('terms', 'like', '%"' . $subject . '"%');
        }
        $today = Carbon::today();

        $query = $query->whereDate('created', '<=', $today);
        $properties = $query->orderBy('created', 'desc')->paginate(15);
        foreach ($properties as $property) {
            $media = $property->getFirstMedia('default');
            $property->thumbnail_url = $media ? $media->getUrl() : null;
        }
        return view('nodes.ruchomosci', compact('properties'));
    }

    public function komunikaty(Request $request)
    {
        $query = Comunicats::query();
        $query->join('c144_nodes_teryts', 'komunikaty.id', '=', 'c144_nodes_teryts.node_id')
            ->select('komunikaty.*', 'c144_nodes_teryts.latitude', 'c144_nodes_teryts.longitude', 'c144_nodes_teryts.miasto');

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = $request->input('radius', 0);

            if ($radius > 0) {
                $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(c144_nodes_teryts.latitude)) * cos(radians(c144_nodes_teryts.longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(c144_nodes_teryts.latitude))))";

                $query->whereRaw("$haversine <= ?", [$radius]);
            } else {
                if ($request->filled('city')) {
                    $city = $request->input('city');
                    $query->where('c144_nodes_teryts.miasto', 'like', '%' . $city . '%');
                }
            }
        }
        $today = Carbon::today();

        $query = $query->whereDate('created', '<=', $today);
        $properties = $query->orderBy('created', 'desc')->paginate(15);
        foreach ($properties as $property) {
            $media = $property->getFirstMedia('default');
            $property->thumbnail_url = $media ? $media->getUrl() : null;
        }
        return view('nodes.komunikaty', compact('properties'));
    }

    public function wierzytelnosci(Request $request)
    {
        $query = Claim::query();
        $query->join('c144_nodes_teryts', 'wierzytelnosci.id', '=', 'c144_nodes_teryts.node_id')
            ->select('wierzytelnosci.*', 'c144_nodes_teryts.latitude', 'c144_nodes_teryts.longitude', 'c144_nodes_teryts.miasto');

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $radius = $request->input('radius', 0);

            if ($radius > 0) {
                $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(c144_nodes_teryts.latitude)) * cos(radians(c144_nodes_teryts.longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(c144_nodes_teryts.latitude))))";

                $query->whereRaw("$haversine <= ?", [$radius]);
            } else {
                if ($request->filled('city')) {
                    $city = $request->input('city');
                    $query->where('c144_nodes_teryts.miasto', 'like', '%' . $city . '%');
                }
            }
        }
        $today = Carbon::today();

        $query = $query->whereDate('created', '<=', $today);
        $properties = $query->orderBy('created', 'desc')->paginate(15);
        foreach ($properties as $property) {
            $media = $property->getFirstMedia('default');
            $property->thumbnail_url = $media ? $media->getUrl() : null;
        }
        return view('nodes.claims', compact('properties'));
    }
}
