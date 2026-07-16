<?php

namespace App\Http\Controllers;

use App\Services\RegionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegionChangeController extends Controller
{
    protected $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function changeRegion($regionCode, Request $request)
    {
        // Validate that the region code exists
        $region = \App\Models\Region::where('code', $regionCode)->where('active', true)->first();

        if (!$region) {
            abort(404, 'Region not found');
        }

        // Set the region in session
        $this->regionService->setCurrentRegion($regionCode);

        // Map region codes to locale codes (same as in RegionMiddleware)
        $localeMap = [
            'us' => 'en-US',
            'uk' => 'en-GB',
            'au' => 'en-AU',
            'ca' => 'en-CA',
            'fr' => 'fr-FR',
            'de' => 'de-DE',
            'it' => 'it-IT',
            'nl' => 'nl-NL',
            'pl' => 'pl-PL',
            'es' => 'es-ES',
            'mx' => 'es-MX',
            'ch' => 'de-CH',
            'lu' => 'lb-LU',
            'fi' => 'fi-FI',
            'no' => 'no-NO',
            'nz' => 'en-NZ',
            'sg' => 'en-SG',
            'at' => 'de-AT',
        ];

        // Set the locale immediately in the session
        $locale = $localeMap[$regionCode] ?? 'en-US';
        Session::put('locale', $locale);
        \App::setLocale($locale);

        // Always redirect to the homepage of the selected region
        if ($regionCode === 'us') {
            $redirectUrl = url('/');
        } else {

            $redirectUrl = url('/' . $regionCode);
        }

        // Make sure no trailing slash is randomly added
        $redirectUrl = rtrim($redirectUrl, '/');
        if (empty($redirectUrl)) {
            $redirectUrl = url('/');
        }

        return redirect()->to($redirectUrl)->withHeaders([
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
}
