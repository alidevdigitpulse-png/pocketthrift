<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\RegionService;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class RegionMiddleware
{
    protected $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function handle(Request $request, Closure $next)
    {
        $firstSegment = trim(strtolower($request->segment(1) ?? ''));
        
        // TEMPORARY DEBUG - remove after fixing
        $debugLog = storage_path('logs/region_debug.log');
        $debugLine = date('H:i:s') . " | URL: " . $request->fullUrl() . " | segment1: '$firstSegment' | session: '" . session('current_region', 'NONE') . "'\n";
        file_put_contents($debugLog, $debugLine, FILE_APPEND);
        
        // Map region codes to locale codes
        $localeMap = [
            'us' => 'en-US', // United States - English
            'uk' => 'en-GB', // United Kingdom - English
            'au' => 'en-AU', // Australia - English
            'ca' => 'en-CA', // Canada - English
            'fr' => 'fr-FR', // France - French
            'de' => 'de-DE', // Germany - German
            'it' => 'it-IT', // Italy - Italian
            'nl' => 'nl-NL', // Netherlands - Dutch
            'pl' => 'pl-PL', // Poland - Polish
            'es' => 'es-ES', // Spain - Spanish
            'mx' => 'es-MX', // Mexico - Spanish
            'ch' => 'de-CH', // Switzerland - German
            'lu' => 'lb-LU', // Luxembourg - French
            'fi' => 'fi-FI', // Finland - Finnish
            'no' => 'no-NO', // Norway - Norwegian
            'nz' => 'en-NZ', // New Zealand - English
            'sg' => 'en-SG', // Singapore - English
            'at' => 'de-AT', // Austria - German
        ];

        // Check if the URL has a valid region code as the first segment
        $isAnyRegionInUrl = !empty($firstSegment) && isset($localeMap[$firstSegment]);
        
        // --- HANDLE /us PREFIX: redirect to clean URL without prefix ---
        // Must update session BEFORE redirecting to prevent stale session causing a loop back
        if ($firstSegment === 'us') {
            session(['current_region' => 'us']);
            session(['locale' => 'en-US']);
            App::setLocale('en-US');
            
            $path = ltrim(substr($request->path(), 2), '/');
            $redirectTo = url($path ?: '/');
            file_put_contents($debugLog, date('H:i:s') . " | REDIRECT (us->clean): $redirectTo\n", FILE_APPEND);
            return redirect()->to($redirectTo);
        }

        // --- SYNC SESSION WITH URL REGION ---
        // If the URL has a valid non-US region, FORCE the session to match it.
        if ($isAnyRegionInUrl) {
            session(['current_region' => $firstSegment]);
            session(['locale' => $localeMap[$firstSegment]]);
            App::setLocale($localeMap[$firstSegment]);
        }

        // Now set the region for the application
        // IMPORTANT: Pass a flag so setAppRegion does NOT override what we just set from the URL
        $this->setAppRegionSafely($isAnyRegionInUrl ? $firstSegment : null);

        // Get current region AFTER session sync and setAppRegion()
        $currentRegion = $this->regionService->getCurrentRegion();

        // --- SET LOCALE FOR TRANSLATIONS ---
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } elseif ($currentRegion && isset($localeMap[$currentRegion->code])) {
            $locale = $localeMap[$currentRegion->code];
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            App::setLocale('en-US'); // default locale
            Session::put('locale', 'en-US');
        }

        // --- Handle redirects for region consistency ---

        // If URL has no region prefix, but the session is set to a non-US region, redirect.
        if (!$isAnyRegionInUrl && session('current_region') && session('current_region') !== 'us') {
            // Avoid redirecting for assets, admin routes, auth routes, etc.
            if ($request->method() === 'GET' && !$request->is('admin/*', 'login', 'register', 'logout', 'password/*', 'change-region/*', 'change-region-to-us', 'auth/*', 'redirect', 'callback')) {
                $sessionRegion = session('current_region');
                $path = $request->path() === '/' ? '' : $request->path();
                
                $redirectUrl = rtrim(url($sessionRegion . ($path ? '/' . ltrim($path, '/') : '')), '/');

                // --- LOOP PREVENTION: Don't redirect if we just came from the same URL ---
                $referer = $request->headers->get('referer');
                if ($referer && str_contains($referer, $redirectUrl)) {
                    \Log::channel('region_debug')->warning("LOOP DETECTED: Not redirecting to $redirectUrl because referer is $referer");
                } else {
                    \Log::channel('region_debug')->info("REDIRECT (add-region): $redirectUrl");
                    return redirect()->to($redirectUrl);
                }
            }
        }

        $response = $next($request);

        // Attach the region cookie to the response if it's not already set
        if (!$request->hasCookie('region')) {
            $regionCode = $currentRegion ? $currentRegion->code : 'us';
            $response->withCookie(cookie('region', $regionCode, 60 * 24 * 30));
        }

        return $response;
    }

    /**
     * Set the app region without overriding URL-based session values.
     * 
     * When $urlRegionCode is provided, it means the URL explicitly has a region,
     * so we should NOT let setAppRegion reset the session to 'us' if the DB lookup fails.
     */
    protected function setAppRegionSafely(?string $urlRegionCode = null): void
    {
        $currentRegion = $this->regionService->getCurrentRegion();
        
        if (!$currentRegion) {
            if ($urlRegionCode) {
                // URL explicitly has a region but DB didn't find it.
                // DON'T reset session to 'us' — just use US region object as fallback
                // but keep session pointing to what the URL says.
                $currentRegion = \App\Models\Region::where('code', 'us')->first();
                // Session stays as-is (pointing to $urlRegionCode)
            } else {
                // No region in URL and session lookup failed — default to US
                $currentRegion = \App\Models\Region::where('code', 'us')->first();
                if ($currentRegion) {
                    Session::put('current_region', 'us');
                }
            }
        }
        
        // Set the region in a global context
        if ($currentRegion) {
            app()->singleton('current_region', function () use ($currentRegion) {
                return $currentRegion;
            });
        }
    }
}
