<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Post;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Class PropertiesController
 * @package App\Http\Controllers
 */
class PropertiesController extends Controller
{
    public function index($slug)
    {
        $today = Carbon::today();

        $property = Property::where('slug', $slug)->firstOrFail();

        $mainMedia = $property->getFirstMedia('default');
        $mainMediaUrl = $mainMedia ? $mainMedia->getUrl() : null;

        $galleryMedia = $property->getMedia('default');

        $properties = Property::whereDate('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate(15);
        $properties->each(function ($property) {
            $property->mainMediaUrl = $property->getFirstMediaUrl('default');
        });
        $comunicats = Post::whereDate('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate(5);
        $comunicats->each(function ($comunicat) {
            $comunicat->mainMediaUrl = $comunicat->getFirstMediaUrl('default');
        });
        $createdDate = Carbon::parse($property->created);
        $formattedDateNumeric = $createdDate->format('d/m/Y');
        $formattedDateText = $createdDate->translatedFormat('j F Y');
        $herb = $property->getFirstMedia('herb');

        return view('node.index', compact('property', 'properties', 'comunicats', 'formattedDateNumeric', 'formattedDateText', 'mainMediaUrl', 'galleryMedia', 'herb'));
    }

    public function printPage($slug)
    {
        $property = Property::where('slug', $slug)->firstOrFail();
        $createdDate = Carbon::parse($property->created);
        $formattedDateNumeric = $createdDate->format('d/m/Y');
        $formattedDateText = $createdDate->translatedFormat('j F Y');
        $fullLocation = $property->getFullLocation();
        $transactionDetails = $property->getTransactionDetails();

        PDF::setOptions(['defaultFont' => 'DejaVu Sans']);

        $pdf = PDF::loadView('print', compact('property', 'formattedDateNumeric', 'formattedDateText', 'fullLocation', 'transactionDetails'));

        return $pdf->stream('property.pdf');
    }
}