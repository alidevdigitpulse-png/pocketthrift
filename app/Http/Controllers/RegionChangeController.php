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

    public function changeRegion($regionCode)
    {
        // Validate that the region code exists
        $region = \App\Models\Region::where('code', $regionCode)->where('active', true)->first();
        
        if (!$region) {
            abort(404, 'Region not found');
        }
        
        // Set the region in session
        $this->regionService->setCurrentRegion($regionCode);
        
        // Calculate the redirect path based on the selected region
        if ($regionCode === 'us') {
            // For US, redirect to the base URL without any region prefix
            return redirect('/');
        } else {
            // For other regions, redirect to the region-prefixed root
            return redirect('/' . $regionCode . '/');
        }
    }
}