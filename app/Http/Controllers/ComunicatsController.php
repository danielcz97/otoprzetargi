<?php

namespace App\Http\Controllers;

use App\Models\Comunicats;
use App\Models\Post;
use Carbon\Carbon;

/**
 * Class ComunicatsController
 * @package App\Http\Controllers
 */
class ComunicatsController extends Controller
{
    public function index($slug)
    {
        $property = Comunicats::where('slug', $slug)->firstOrFail();
        $properties = Comunicats::orderBy('created', 'desc')->paginate(15);
        $comunicats = Post::orderBy('created', 'desc')->paginate(5);
        $createdDate = Carbon::parse($property->created);
        $formattedDateNumeric = $createdDate->format('d/m/Y');
        $formattedDateText = $createdDate->translatedFormat('j F Y');

        return view('node.comunicats', compact('property', 'properties', 'comunicats', 'formattedDateNumeric', 'formattedDateText'));
    }
}