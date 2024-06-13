<?php

namespace App\Http\Controllers;

use App\Models\MovableProperty;
use App\Models\Post;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


/**
 * Class MovablePropertyController
 * @package App\Http\Controllers
 */
class MovablePropertyController extends Controller
{
    public function index($slug)
    {
        $today = Carbon::today();

        $property = MovableProperty::where('slug', $slug)->firstOrFail();
        $mainMediaUrl = $property->getMediaUrl();

        $galleryMedia = $property->getMedia('default')->reject(function ($media) use ($property) {
            return $media->id === $property->getFirstMedia('default')->id;
        });

        $properties = MovableProperty::whereDate('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate(15);
        $properties->each(function ($property) {
            $property->mainMediaUrl = $property->getMediaUrl();
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
        $maps = 'https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_MAPS_API_KEY') . '&libraries=places';

        return view('node.movable', compact('property', 'properties', 'comunicats', 'formattedDateNumeric', 'formattedDateText', 'mainMediaUrl', 'galleryMedia', 'maps'));
    }

    public function printPage($slug)
    {
        $property = MovableProperty::where('slug', $slug)->firstOrFail();
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