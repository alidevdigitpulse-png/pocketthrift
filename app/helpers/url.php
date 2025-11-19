<?php

if (!function_exists('region_url')) {
    function region_url($path = '', $parameters = [], $secure = null)
    {
        $regionService = app(\App\Services\RegionService::class);
        $currentRegion = $regionService->getCurrentRegionCode();

        if ($currentRegion && $currentRegion !== 'us') {
            $path = $currentRegion . '/' . ltrim($path, '/');
        }

        return url($path, $parameters, $secure);
    }
}
