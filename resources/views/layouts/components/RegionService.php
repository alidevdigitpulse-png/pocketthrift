<?php

namespace App\Services;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RegionService
{
    protected $regions;
    protected $currentRegion;

    public function __construct(Request $request)
    {
        $this->regions = Cache::remember('all_active_regions', 3600, function () {
            return Region::where('active', 1)->orderBy('sort')->get()->keyBy('code');
        });

        $this->determineCurrentRegion($request);
    }

    /**
     * Determines the current region from the URL segment or cookie.
     */
    private function determineCurrentRegion(Request $request)
    {
        $regionCode = $request->segment(1);

        // Check if the first segment is a valid region code
        if ($regionCode && $this->regions->has($regionCode)) {
            $this->currentRegion = $this->regions->get($regionCode);
        } else {
            // Fallback to cookie or default to 'us'
            $cookieRegionCode = $request->cookie('region', 'us');
            $this->currentRegion = $this->regions->get($cookieRegionCode, $this->regions->get('us'));
        }
    }

    /**
     * Get the currently determined region.
     *
     * @return \App\Models\Region|null
     */
    public function getCurrentRegion()
    {
        return $this->currentRegion;
    }

    /**
     * Get all active regions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRegions()
    {
        return $this->regions->values();
    }

    /**
     * Get all region codes as an array.
     *
     * @return array
     */
    public function getRegionCodes()
    {
        return $this->regions->keys()->toArray();
    }

    /**
     * Set the current region in the application container.
     */
    public function setAppRegion()
    {
        app()->instance('current_region', $this->getCurrentRegion());
    }
}