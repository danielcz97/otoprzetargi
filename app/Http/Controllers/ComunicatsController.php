<?php

namespace App\Http\Controllers;

use App\Models\Comunicats;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers;
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

        return view('nodes.comunicats', compact('property', 'properties'));
    }
}