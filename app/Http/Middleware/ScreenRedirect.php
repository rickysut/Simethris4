<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScreenRedirect
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userRole = Auth::user()->roles[0]->title;

            $screenSize = $request->cookie('screen_size', 'desktop');

            if ($userRole === 'Verifikator') {
                if ($screenSize === 'mobile') {
                    return redirect()->route('2024.verifikator.mobile');
                }
            }
            if ($userRole === '	Spatial Administrator') {
                if ($screenSize === 'mobile') {
                    return redirect()->route('2024.spatial.mobile');
                }
            }
        }

        return $next($request);
    }
}
