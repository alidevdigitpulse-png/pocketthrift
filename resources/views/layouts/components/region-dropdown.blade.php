@php
    $regionService = app(\App\Services\RegionService::class);
    $currentRegion = $regionService->getCurrentRegion();
    $regions = $regionService->getAllRegions();
@endphp

<div class="dropdown">
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
               onclick="changeRegion('{{ $region->code }}', '{{ $region->country }}')">
                {{ $region->country }}
            </a>
        </li>
    @endforeach
</ul>
</div>

<script>
function changeRegion(region, regionName) {
    const currentUrl = window.location.href;
    const baseUrl = '{{ url('/') }}'; // Use Laravel's URL generator for base URL
    const path = window.location.pathname;
    const parts = path.split('/').filter(Boolean);

    // Region list dynamically from backend:
    const validRegions = @json(app(\App\Services\RegionService::class)->getAllRegions()->pluck('code')->map(fn($c) => strtolower($c)));

    // Remove old region code if the first part is a valid region
    if (parts.length && validRegions.includes(parts[0].toLowerCase())) {
        parts.shift();
    }

    // Add the new region to the start of the path
    parts.unshift(region.toLowerCase());

    const newPath = '/' + parts.join('/');
    window.location.href = baseUrl + newPath;
}
</script>
