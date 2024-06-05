<?php

namespace App\Http\Middleware;

use App\Models\Claim;
use App\Models\Comunicats;
use App\Models\MovableProperty;
use Closure;
use Illuminate\Http\Request;
use App\Models\Property;

class CountViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $segments = $request->segments();
        $slug = end($segments);
        if ($request->is('admin/*')) {
            return $next($request);
        }
        if ($slug) {
            $model = null;

            if (in_array('nieruchomosci', $segments)) {
                $model = Property::where('slug', $slug)->first();
            } elseif (in_array('ruchomosci', $segments)) {
                $model = MovableProperty::where('slug', $slug)->first();
            } elseif (in_array('komunikaty', $segments)) {
                $model = Comunicats::where('slug', $slug)->first();
            } elseif (in_array('wierzytelnosci', $segments)) {
                $model = Claim::where('slug', $slug)->first();
            }

            if ($model) {
                $model->increment('views');
            }
        }

        return $next($request);
    }
}
