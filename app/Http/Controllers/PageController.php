<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Region;
use App\Services\RegionService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    /**
     * Display a page based on region and slug
     *
     * @param string|null $regionCode
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($regionCode = null, $slug)
    {
        // Set the region based on URL or default
        if ($regionCode) {
            $this->regionService->setCurrentRegion($regionCode);
        }

        // Get the current region
        $currentRegion = $this->regionService->getCurrentRegion();
        
        // Find page by slug and region
        $page = Page::where('slug', $slug)
                    ->when($currentRegion, function($query) use ($currentRegion) {
                        return $query->where('region_id', $currentRegion->id);
                    }, function($query) {
                        // If no region is set, default to showing pages without a region or default region
                        return $query->whereNull('region_id');
                    })
                    ->first();

        if (!$page) {
            // If no region-specific page found, try to find a default page
            $page = Page::where('slug', $slug)->whereNull('region_id')->first();
        }

        if (!$page) {
            abort(404, 'Page not found');
        }

        return view('page.show', compact('page'));
    }

    /**
     * Show all pages for the current region
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $currentRegion = $this->regionService->getCurrentRegion();
        
        $pages = Page::when($currentRegion, function($query) use ($currentRegion) {
            return $query->where('region_id', $currentRegion->id);
        }, function($query) {
            return $query->whereNull('region_id');
        })
        ->where('status', 0) // Only active pages
        ->with('region') // Load region relationship for display
        ->paginate(12);

        return view('page.index', compact('pages'));
    }
}