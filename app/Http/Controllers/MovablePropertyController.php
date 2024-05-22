<?php

namespace App\Http\Controllers;

use App\Models\MovableProperty;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers;
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

        return view('nodes.movable', compact('property', 'properties'));
    }
}