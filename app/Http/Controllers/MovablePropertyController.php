<?php

namespace App\Http\Controllers;

use App\Models\MovableProperty;
use App\Models\Post;
use Carbon\Carbon;


/**
 * Class MovablePropertyController
 * @package App\Http\Controllers
 */
class MovablePropertyController extends Controller
{
    public function index($slug)
    {
        $property = MovableProperty::where('slug', $slug)->firstOrFail();
        $properties = MovableProperty::orderBy('created', 'desc')->paginate(15);
        $comunicats = Post::orderBy('created', 'desc')->paginate(5);
        $createdDate = Carbon::parse($property->created);
        $formattedDateNumeric = $createdDate->format('d/m/Y');
        $formattedDateText = $createdDate->translatedFormat('j F Y'); 

        return view('nodes.movable', compact('property', 'properties', 'comunicats', 'formattedDateNumeric', 'formattedDateText'));
    }
}