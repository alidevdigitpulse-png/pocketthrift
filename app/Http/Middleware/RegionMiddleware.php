<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RegionService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\App; // ← Added

class RegionMiddleware
{
    protected $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function handle(Request $request, Closure $next)
    {
        $urlRegionCode = $request->segment(1);
        $validRegionCodes = $this->regionService->getRegionCodes();

        // Check if the first segment is a valid region code.
        $isUrlRegionValid = in_array($urlRegionCode, $validRegionCodes);

        // If the first segment is not a valid region, we should not treat it as a region.
        if (!$isUrlRegionValid) {
            $urlRegionCode = null;
        }

        // Set the region for the application to use in controllers/models
        $this->regionService->setAppRegion();
        
        $currentRegion = $this->regionService->getCurrentRegion();
        $cookieRegionCode = $request->cookie('region', 'us');

        // --- SET LOCALE FOR TRANSLATIONS ---
        if ($currentRegion) {
            App::setLocale($currentRegion->code); // ← Added line
        } else {
            App::setLocale('us'); // default locale
        }

        // --- Handle redirects for region consistency ---

        // 1. If URL has a valid region prefix that is different from the session, update the session.
        if ($isUrlRegionValid && $urlRegionCode !== session('current_region')) {
            session(['current_region' => $urlRegionCode]);
        }

        // 2. If URL has no region prefix, but the session is set to a non-default region, redirect.
        if (!$urlRegionCode && session('current_region') && session('current_region') !== 'us') {
            // Avoid redirecting for assets or special URIs
            if ($request->method() === 'GET' && !$request->is('admin/*|login|register|logout')) {
                $path = $request->path() === '/' ? '' : $request->path();
                return redirect()->to(session('current_region') . '/' . $path);
            }
        }

        // 3. If the URL has a 'us' prefix, redirect to the version without it.
        if ($urlRegionCode === 'us') {
            $path = ltrim(substr($request->path(), 2), '/');
            return redirect()->to($path);
        }

        $response = $next($request);

        // Attach the region cookie to the response if it's not already set
        if (!$request->hasCookie('region')) {
            $regionCode = $currentRegion ? $currentRegion->code : 'us';
            $response->withCookie(cookie('region', $regionCode, 60 * 24 * 30));
        }

        return $response;
    }
}
