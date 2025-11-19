<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Region;
use Illuminate\Support\Facades\Session;

class RegionFilter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        
        // Get the first segment which might be the region code
        $segments = explode('/', $path);
        $firstSegment = !empty($segments[0]) ? $segments[0] : null;
        
        // Get all active regions
        $regionCodes = Region::where('active', true)->pluck('code')->toArray();
        
        // Check if the first segment is a valid region code
        if ($firstSegment && in_array(strtolower($firstSegment), $regionCodes)) {
            $regionCode = strtolower($firstSegment);
            
            // Valid region in URL, store it in session
            session(['current_region' => $regionCode]);
            
            // Store region in request for use in controllers if needed
            $request->attributes->set('region', $regionCode);
            
            return $next($request);
        } else {
            // No region in URL, use session value or default to 'us'
            $currentRegion = session('current_region', 'us');
            
            // Don't redirect for static assets, API calls, admin, etc.
            $excludePaths = [
                'admin', 'user', 'auth', 'login', 'register', 'password', 'logout',
                'css', 'js', 'images', 'img', 'storage', 'public', 'vendor'
            ];
            
            $startsWithExcluded = false;
            foreach ($excludePaths as $exclude) {
                if (str_starts_with($path, $exclude)) {
                    $startsWithExcluded = true;
                    break;
                }
            }
            
            // For root path, set default region in session
            if ($path === '' || $path === '/') {
                session(['current_region' => 'us']);
            }
            
            // Redirect to region-prefixed URL if not on excluded paths and not on root,
            // and the region is not 'us' but also not in the URL
            if (!$startsWithExcluded && $path !== '' && $path !== '/' && 
                $currentRegion !== 'us' && !in_array($firstSegment, $regionCodes)) {
                
                // If the current region is not 'us' and it's not in the URL, 
                // redirect to the region-prefixed version
                $redirectPath = $currentRegion . '/' . ltrim($path, '/');
                
                return redirect()->to($redirectPath);
            }
            
            // For non-region paths, ensure we have a region set in session
            if (!session()->has('current_region')) {
                session(['current_region' => 'us']);
            }
            
            return $next($request);
        }
    }
}
