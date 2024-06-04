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
        $properties = MovableProperty::whereDate('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate(15);
        $comunicats = Post::whereDate('created', '<=', $today)
            ->orderBy('created', 'desc')
            ->paginate(5);
        $createdDate = Carbon::parse($property->created);
        $formattedDateNumeric = $createdDate->format('d/m/Y');
        $formattedDateText = $createdDate->translatedFormat('j F Y');

        return view('node.movable', compact('property', 'properties', 'comunicats', 'formattedDateNumeric', 'formattedDateText'));
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