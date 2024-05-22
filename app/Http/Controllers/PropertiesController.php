<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers;
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

        return view('nodes.index', compact('property', 'properties'));
    }
}