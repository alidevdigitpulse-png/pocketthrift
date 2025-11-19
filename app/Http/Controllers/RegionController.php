<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class RegionController extends Controller
{
    public function setRegionToUs(Request $request)
    {
        session(['current_region' => 'us']);

        $referer = $request->headers->get('referer');
        $previousUrl = parse_url($referer);
        $path = $previousUrl['path'] ?? '/';

        $segments = explode('/', trim($path, '/'));
        $regionService = app(\App\Services\RegionService::class);
        $validRegionCodes = $regionService->getRegionCodes();

        // Check if the first segment is a region code and remove it
        if (count($segments) > 0 && in_array($segments[0], $validRegionCodes)) {
            array_shift($segments);
        }

        $redirectPath = implode('/', $segments);

        return redirect('/' . $redirectPath);
    }
}
