/**
 * Updated terms method that should replace the existing one in HomeController
 */
public function terms()
{
    // Get current region from the region service (like other methods in this controller)
    $currentRegion = $this->regionService->getCurrentRegionCode();
    
    // Find the T&C page for the current region with flexible matching
    $page = Page::whereHas('region', function($query) use ($currentRegion) {
        $query->where('code', $currentRegion);
    })
    ->where(function($query) {
        $query->where('slug', 'like', '%terms%')
              ->orWhere('name', 'LIKE', '%Terms%')
              ->orWhere('name', 'LIKE', '%terms%')
              ->orWhere('slug', 'like', '%Terms%');
    })
    ->first();

    // If no page found for current region, fall back to default region (US)
    if (!$page) {
        $page = Page::whereHas('region', function($query) {
            $query->where('code', 'us');
        })
        ->where(function($query) {
            $query->where('slug', 'like', '%terms%')
                  ->orWhere('name', 'LIKE', '%Terms%')
                  ->orWhere('name', 'LIKE', '%terms%')
                  ->orWhere('slug', 'like', '%Terms%');
        })
        ->first();
    }

    $regions = Region::all(); // For region selector dropdown

    return view('terms_and_conditions', compact('page', 'regions', 'currentRegion'));
}