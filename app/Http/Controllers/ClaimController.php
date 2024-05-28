<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Post;
use Carbon\Carbon;


use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index($slug)
    {
        $property = Claim::where('slug', $slug)->firstOrFail();
        $properties = Claim::orderBy('created', 'desc')->paginate(15);
        $comunicats = Post::orderBy('created', 'desc')->paginate(5);
        $createdDate = Carbon::parse($property->created);
        $formattedDateNumeric = $createdDate->format('d/m/Y');
        $formattedDateText = $createdDate->translatedFormat('j F Y');

        return view('node.claim', compact('property', 'properties', 'comunicats', 'formattedDateNumeric', 'formattedDateText'));
    }
}