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
        $property = Property::where('slug', $slug)->firstOrFail();
        $properties = Property::orderBy('created', 'desc')->paginate(15);
        $comunicats = Post::orderBy('created', 'desc')->paginate(5);
        $createdDate = Carbon::parse($property->created);
        $formattedDateNumeric = $createdDate->format('d/m/Y');
        $formattedDateText = $createdDate->translatedFormat('j F Y');

        return view('node.index', compact('property', 'properties', 'comunicats', 'formattedDateNumeric', 'formattedDateText'));
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