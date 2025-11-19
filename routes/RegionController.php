<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Services\RegionService;

class RegionChangeController extends Controller
{
    protected $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function changeRegion(Request $request, $regionCode)
    {
        $previousUrl = $request->query('previous_url', url('/'));

        // Parse the previous URL to get the path
        $path = parse_url($previousUrl, PHP_URL_PATH);
        $segments = explode('/', ltrim($path, '/'));

        // Get all available regions
        $allRegions = $this->regionService->getRegionCodes();

        // Remove region prefix if exists (e.g. /uk, /ca)
        if (isset($segments[0]) && in_array($segments[0], $allRegions)) {
            array_shift($segments);
        }

        $pathWithoutRegion = implode('/', $segments);

        // Construct new path
        // 👉 If region is 'us' (default), don't prefix the URL
        $newPath = ($regionCode !== 'us') ? '/' . $regionCode . '/' . $pathWithoutRegion : '/' . $pathWithoutRegion;

        // Clean double slashes (like //)
        $newPath = preg_replace('#/+#', '/', $newPath);

        // Store region in cookie (30 days)
        $cookie = Cookie::make('region', $regionCode, 60 * 24 * 30);

        // Redirect to new path
        return redirect($newPath)->withCookie($cookie);
    }
}
