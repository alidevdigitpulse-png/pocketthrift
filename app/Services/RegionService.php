<?php

namespace App\Services;

use App\Models\Region;
use Illuminate\Support\Facades\Session;

class RegionService
{
    /**
     * Get the current region based on session or default to 'us'
     * 
     * @return \App\Models\Region|null
     */
    public function getCurrentRegion()
    {
        $regionCode = Session::get('current_region', 'us');
        return Region::where('code', $regionCode)->where('active', true)->first();
    }

    /**
     * Get the current region code
     * 
     * @return string
     */
    public function getCurrentRegionCode()
    {
        return Session::get('current_region', 'us');
    }

    /**
     * Set the current region in session
     * 
     * @param string $regionCode
     * @return void
     */
    public function setCurrentRegion($regionCode)
    {
        $region = Region::where('code', $regionCode)->where('active', true)->first();
        
        if ($region) {
            Session::put('current_region', $regionCode);
        } else {
            // If invalid region code, default to 'us'
            Session::put('current_region', 'us');
        }
    }

    /**
     * Get all available regions
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRegions()
    {
        return Region::where('active', true)->orderBy('sort', 'asc')->get();
    }

    /**
     * Check if the current region is the default (US)
     * 
     * @return bool
     */
    public function isDefaultRegion()
    {
        return $this->getCurrentRegionCode() === 'us';
    }

    /**
     * Get region URL path based on current region
     * 
     * @param string $path
     * @return string
     */
    public function getRegionPath($path = '')
    {
        $currentRegion = $this->getCurrentRegionCode();
        
        if ($currentRegion === 'us' || empty($currentRegion)) {
            // For US, don't prefix the path
            return $path;
        }
        
        return $currentRegion . '/' . ltrim($path, '/');
    }

    /**
     * Generate a URL with region prefix when appropriate
     * 
     * @param string $path
     * @return string
     */
    public function getRegionUrl($path = '')
    {
        $currentRegion = $this->getCurrentRegionCode();
        $baseUrl = url('');
        
        if ($currentRegion === 'us' || empty($currentRegion)) {
            // For US, use base URL without region prefix
            return $baseUrl . ($path ? '/' . ltrim($path, '/') : '/');
        } else {
            // For other regions, add the region prefix
            return $baseUrl . '/' . $currentRegion . ($path ? '/' . ltrim($path, '/') : '/');
        }
    }

    /**
     * Get all region URLs for a specific path for hreflang tags
     * 
     * @param string $path
     * @return array
     */
    public function getAllRegionUrls($path = '')
    {
        $urls = [];
        $regions = $this->getAllRegions();
        $baseUrl = url('');
        $path = ltrim($path, '/');
        $cleanPath = $path; // Path without region prefix

        foreach ($regions as $region) {
            $regionUrl = $baseUrl;
            if ($region->code !== 'us') {
                $regionUrl .= '/' . $region->code;
            }
            if ($cleanPath) {
                $regionUrl .= '/' . $cleanPath;
            }
            $urls[$region->code] = $regionUrl;
        }

        return $urls;
    }

    /**
     * Get all active region codes
     * 
     * @return array
     */
    public function getRegionCodes()
    {
        return Region::where('active', true)->pluck('code')->toArray();
    }

    /**
     * Set the current region for the application
     * 
     * @return void
     */
    public function setAppRegion()
    {
        $currentRegion = $this->getCurrentRegion();
        // Ensure we have a valid region, default to US if not found
        if (!$currentRegion) {
            $currentRegion = Region::where('code', 'us')->first();
            if ($currentRegion) {
                Session::put('current_region', 'us');
            }
        }
        
        // Set the region in a global context if needed
        if ($currentRegion) {
            app()->singleton('current_region', function () use ($currentRegion) {
                return $currentRegion;
            });
        }
    }
}