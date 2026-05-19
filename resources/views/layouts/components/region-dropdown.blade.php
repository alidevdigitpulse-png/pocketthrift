@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();
    $regions = $regionService->getAllRegions();
@endphp

<div class="dropup">
    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" id="regionDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.875rem; padding: 0.25rem 0.5rem;">
        <i class="fas fa-globe me-1"></i>
        {{ $currentRegion ? substr($currentRegion->country, 0, 12) . (strlen($currentRegion->country) > 12 ? '...' : '') : 'Region' }}
    </button>
    <ul class="dropdown-menu" aria-labelledby="regionDropdown">
    {{-- <li>
        <a class="dropdown-item {{ $currentRegion && $currentRegion->code === 'us' ? 'active' : '' }}" 
           href="{{ route('change.region.us') }}">
            United States
        </a>
    </li> --}}

    @foreach($regions as $region)
        <li>
            <a class="dropdown-item {{ $currentRegion && $currentRegion->code === $region->code ? 'active' : '' }}" 
               href="javascript:void(0);" 
               style="color: black;"
               onclick="changeRegion('{{ $region->code }}', '{{ $region->country }}')">
                {{ $region->country }}
            </a>
        </li>
    @endforeach
</ul>
</div>

<script>
function changeRegion(region, regionName) {
    // Use the Laravel route to change region
    // Now simplified to always go to the region's home page
    const changeRegionUrl = '{{ url('/change-region') }}/' + region;
    window.location.href = changeRegionUrl;
}
</script>
