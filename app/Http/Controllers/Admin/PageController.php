<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use App\Models\{Page, Region, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function removeColumns($columns, $columsToBeRemove)
    {
        foreach ($columsToBeRemove as $value) {
            if (($key = array_search($value, $columns)) !== false) {
                unset($columns[$key]);
            }
        }
        return $columns;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $regionFilter = $request->region;
        $data = new Page();

        $query = Page::with([]);

        // Check if the current user is a region-specific user
        $user = auth()->user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        if (!$isAdmin && $user->assigned_regions) {
            // For region-specific users, only show pages that belong to their assigned region
            $userRegionCode = $user->assigned_regions; // Get the user's assigned region code (e.g., 'us', 'uk')

            // Filter pages that have this region code in their country_codes or have null/empty country_codes (available globally)
            $query->where(function($q) use ($userRegionCode) {
                $q->whereNull('country_codes')
                  ->orWhere('country_codes', '[]')
                  ->orWhere('country_codes', '')
                  ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($userRegionCode)])
                  ->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$userRegionCode]);
            });
        }

        if ($regionFilter) {
            $regionCode = \App\Models\Region::find($regionFilter);
            if($regionCode) {
                $regionCode = $regionCode->code;
                // Filter by pages that have this region code in their country_codes field
                $query->where(function($q) use ($regionCode) {
                    $q->whereNull('country_codes')
                      ->orWhere('country_codes', '[]')
                      ->orWhere('country_codes', '')
                      ->orWhereRaw("JSON_VALID(country_codes) AND JSON_CONTAINS(country_codes, ?)", [json_encode($regionCode)])
                      ->orWhereRaw("FIND_IN_SET(?, REPLACE(country_codes, ' ', '')) > 0", [$regionCode]);
                });
            }
        }

        if ($search != null) {
            $table = $data->getTable();

            $columns = $this->removeColumns(Schema::getColumnListing($table), ['created_at', 'updated_at', 'deleted_at', 'id']);

            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
            $data = $query->orderBy('title')->paginate(12);

            if ($request->onChange == true) {
                // Add route information to the data for JS
                $user = auth()->user();
                $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

                foreach ($data->items() as $item) {
                    if (Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1) {
                        // For region users, use region page routes
                        $item->edit_url = route('admin.region.page.edit', $item->id);
                        $item->delete_url = route('admin.region.page.destroy', $item->id);
                    } else {
                        // For admin users, use standard admin routes
                        $item->edit_url = $isAdmin ? route('admin.page.edit', $item->id) : route('admin.page.edit', $item->id);
                        $item->delete_url = $isAdmin ? route('admin.page.destroy', $item->id) : route('admin.page.destroy', $item->id);
                    }
                }

                return response()->json(['status' => true, 'data' => $data, 'lastPage' => $data->lastPage()]);
            }
        } else {
            $data = $query->orderBy('title')->paginate(12);
            if ($request->onChange == true) {
                // Add route information to the data for JS
                $user = auth()->user();
                $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

                foreach ($data->items() as $item) {
                    if (Auth::user()->assigned_regions && !Auth::user()->hasRole('admin') && Auth::user()->role != 1) {
                        // For region users, use region page routes
                        $item->edit_url = route('admin.region.page.edit', $item->id);
                        $item->delete_url = route('admin.region.page.destroy', $item->id);
                    } else {
                        // For admin users, use standard admin routes
                        $item->edit_url = $isAdmin ? route('admin.page.edit', $item->id) : route('admin.page.edit', $item->id);
                        $item->delete_url = $isAdmin ? route('admin.page.destroy', $item->id) : route('admin.page.destroy', $item->id);
                    }
                }

                return response()->json(['status' => true, 'data' => $data, 'lastPage' => $data->lastPage()]);
            }
        }

        $regions = Region::all();

        // For region-specific users, only show their region in the filter dropdown
        if (!$isAdmin && $user->assigned_regions) {
            $regions = $regions->where('code', $user->assigned_regions);
        }

        return view('admin.page.index', compact('data', 'regions', 'regionFilter'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = null;
        $user = auth()->user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        // For region-specific users, only show their region
        if (!$isAdmin && $user->assigned_regions) {
            $regions = \App\Models\Region::where('code', $user->assigned_regions)->get();
        } else {
            $regions = \App\Models\Region::all();
        }

        return view('admin.page.create', compact('data', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');
        
        // Handle page creation based on user type (admin or region user)
        if (!$isAdmin) {
            // Check if user is a region user (has assigned regions)
            if (empty($user->assigned_regions)) {
                return response()->json(['status' => false, 'message' => 'You do not have a valid region assigned.']);
            }

            $userRegionCode = $user->assigned_regions;
            \Log::info('Region user attempting to create page', ['user_id' => $user->id, 'assigned_regions' => $userRegionCode]);

            // Automatically append region code to page name
            $pageName = $request->input('title');
            $newPageName = $pageName . '-' . $userRegionCode;
            $request->merge(['title' => $newPageName]);

            // Ensure that country_codes includes the user's region if not already specified
            $countryCodes = $request->input('country_codes', []);
            if (empty($countryCodes)) {
                // If no country codes specified, set it to just user's region
                $request->merge(['country_codes' => [$userRegionCode]]);
            } else {
                // If country codes are specified, ensure user's region is in the list
                // Since this is coming from form input, it might be an array or string depending on how it was sent,
                // but typically checkboxes send an array.
                $inputCodes = is_array($countryCodes) ? $countryCodes : explode(',', $countryCodes);
                
                if (!in_array($userRegionCode, $inputCodes)) {
                    return response()->json(['status' => false, 'message' => 'You can only create pages for your assigned region.']);
                }
            }

            \Log::info('Modified page name for region user', ['original_name' => $pageName, 'new_name' => $newPageName]);

            $this->validate($request, [
                'title' => 'required'
            ]);
        } else {
            $this->validate($request, [
                'title' => 'required'
            ]);
        }

        $request->request->remove('_token');
        $data = $request->input();

        // Ensure required fields are present
        if (empty($data['title'])) {
            return response()->json(['status' => false, 'message' => 'Title is required']);
        }

        // Process country codes if provided
        if (isset($data['country_codes']) && is_array($data['country_codes'])) {
            $data['country_codes'] = implode(',', $data['country_codes']);
        }

        // Log the data being inserted for debugging
        \Log::info('Page creation data: ', $data);

        try {
            $page = new Page();
            $page->title = $data['title'];
            $page->seo_title = $data['seo_title'] ?? null;
            $page->seo_meta_keyword = $data['seo_meta_keyword'] ?? null;
            $page->url_slug = $data['url_slug'] ?? Str::slug($data['title']);
            $page->meta_description = $data['meta_description'] ?? null;
            $page->meta_robots = $data['meta_robots'] ?? null;
            $page->content_body = $data['content_body'] ?? null;
            $page->country_codes = $data['country_codes'] ?? null;
            $page->start_date = $data['start_date'] ?? null;
            $page->end_date = $data['end_date'] ?? null;
            $page->active = $data['active'] ?? 1;
            $page->sort = $data['sort'] ?? 0;
            $page->sort = $data['sort'] ?? 0;
            $page->created_by = auth()->id();

            \Log::info('Attempting to save page with data: ', [
                'title' => $page->title,
                'country_codes' => $page->country_codes,
                'active' => $page->active
            ]);

            $result = $page->save();

            if ($result) {
                \Log::info('Page creation successful, ID: ' . $page->id);
                return response()->json(['status' => true, 'message' => 'Page Successfully Created', 'page_id' => $page->id]);
            } else {
                \Log::error('Page creation returned false');
                return response()->json(['status' => false, 'message' => 'Page creation failed - save returned false']);
            }
        } catch (\Exception $e) {
            \Log::error('Page creation failed: ' . $e->getMessage());
            \Log::error('Data that failed: ', $data);
            return response()->json(['status' => false, 'message' => 'Page creation failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);

        // Check if the current user is allowed to view this page (based on region)
        $user = auth()->user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        if (!$isAdmin) {
            // Only allow region users to view pages that are available in their region
            if (empty($user->assigned_regions)) {
                abort(403, 'You do not have a valid region assigned.');
            }

            $userRegionCode = $user->assigned_regions;

            // Check if this page is available in the user's region
            $isPageAvailableInUserRegion = true;
            $pageCountryCodes = $page->country_codes;

            if (!empty($pageCountryCodes) && is_array($pageCountryCodes) && count($pageCountryCodes) > 0) {
                // If page has specific country codes, check if user's region code is in the list
                $isPageAvailableInUserRegion = in_array($userRegionCode, $pageCountryCodes);
            } elseif (!empty($pageCountryCodes) && is_string($pageCountryCodes)) {
                 $isPageAvailableInUserRegion = strpos($pageCountryCodes, $userRegionCode) !== false;
            }
            // If page has no specific country codes (empty), it's available to all regions

            if (!$isPageAvailableInUserRegion) {
                abort(403, 'You do not have permission to view this page.');
            }
        }

        return view('admin.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $data = Page::findOrFail($id);

        // Check if the current user is allowed to edit this page (based on region)
        $user = auth()->user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        if (!$isAdmin) {
            // Only allow region users to edit pages that are available in their region
            if (empty($user->assigned_regions)) {
                abort(403, 'You do not have a valid region assigned.');
            }

            $userRegionCode = $user->assigned_regions;

            // Check if this page is available in the user's region
            $isPageAvailableInUserRegion = true;
            $pageCountryCodes = $data->country_codes;

            if (!empty($pageCountryCodes) && is_array($pageCountryCodes) && count($pageCountryCodes) > 0) {
                // If page has specific country codes, check if user's region code is in the list
                $isPageAvailableInUserRegion = in_array($userRegionCode, $pageCountryCodes);
            } elseif (!empty($pageCountryCodes) && is_string($pageCountryCodes)) {
                 $isPageAvailableInUserRegion = strpos($pageCountryCodes, $userRegionCode) !== false;
            }
            // If page has no specific country codes (empty), it's available to all regions

            if (!$isPageAvailableInUserRegion) {
                abort(403, 'You do not have permission to edit this page.');
            }
        }

        // For region-specific users, only show their region in the dropdown
        if (!$isAdmin && $user->assigned_regions) {
            $regions = \App\Models\Region::where('code', $user->assigned_regions)->get();
        } else {
            $regions = \App\Models\Region::all();
        }

        return view('admin.page.create', compact('data', 'regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $user = auth()->user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        // Check if the user can update this page based on region
        if (!$isAdmin) {
            // Check if user has assigned regions
            if (empty($user->assigned_regions)) {
                abort(403, 'You do not have a valid region assigned.');
            }

            $userRegionCode = $user->assigned_regions;

            // Check if this page is available in the user's region
            $isPageAvailableInUserRegion = true;
            $pageCountryCodes = $page->country_codes;

            if (!empty($pageCountryCodes) && is_array($pageCountryCodes) && count($pageCountryCodes) > 0) {
                // If page has specific country codes, check if user's region code is in the list
                $isPageAvailableInUserRegion = in_array($userRegionCode, $pageCountryCodes);
            } elseif (!empty($pageCountryCodes) && is_string($pageCountryCodes)) {
                 $isPageAvailableInUserRegion = strpos($pageCountryCodes, $userRegionCode) !== false;
            }
            // If page has no specific country codes (empty), it's available to all regions

            if (!$isPageAvailableInUserRegion) {
                abort(403, 'You do not have permission to update this page.');
            }

            // For region-specific users, ensure they don't change the region in country_codes if they don't have permission
            // If they're trying to change country_codes, make sure they can manage the regions they're adding
            $newCountryCodes = $request->input('country_codes');
            if ($newCountryCodes) {
                foreach ($newCountryCodes as $code) {
                    if ($code !== $userRegionCode) {
                        // User is trying to add a region they don't manage
                        abort(403, 'You can only manage pages in your assigned region.');
                    }
                }
            }

            // Automatically append region code to page name for non-admin users
            $pageName = $request->input('title');
            // Remove existing region code if present and re-append the correct one
            $baseName = preg_replace('/-\w+$/', '', $pageName); // Remove any trailing region code like '-ca'
            $request->merge(['title' => $baseName . '-' . $userRegionCode]);

            $this->validate($request, [
                'title' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'title' => 'required',
            ]);
        }

        $request->request->remove('_token');
        $request->request->remove('_method');
        $data = $request->input();

        // Process country codes if provided
        if (isset($data['country_codes']) && is_array($data['country_codes'])) {
            $data['country_codes'] = implode(',', $data['country_codes']);
        }

        try {
            $page->title = $data['title'];
            $page->seo_title = $data['seo_title'] ?? null;
            $page->seo_meta_keyword = $data['seo_meta_keyword'] ?? null;
            $page->url_slug = $data['url_slug'] ?? Str::slug($data['title']);
            $page->meta_description = $data['meta_description'] ?? null;
            $page->meta_robots = $data['meta_robots'] ?? null;
            $page->content_body = $data['content_body'] ?? null;
            $page->country_codes = $data['country_codes'] ?? $page->country_codes;
            $page->start_date = $data['start_date'] ?? null;
            $page->end_date = $data['end_date'] ?? null;
            $page->active = $data['active'] ?? 0;
            $page->sort = $data['sort'] ?? 0;
            $page->updated_by = auth()->id();

            $result = $page->save();

            if ($result) {
                \Log::info("Page updated successfully by user role: " . ($isAdmin ? 'admin' : 'region user'));
                return response()->json(['status' => true, 'message' => 'Page Successfully Updated']);
            } else {
                return response()->json(['status' => false, 'message' => 'Page update failed']);
            }
        } catch (\Exception $e) {
            \Log::error('Page update failed: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Page update failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        // Check if the current user is allowed to delete this page (based on region)
        $user = auth()->user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        if (!$isAdmin) {
            // Only allow region users to delete pages that are available in their region
            if (empty($user->assigned_regions)) {
                abort(403, 'You do not have a valid region assigned.');
            }

            $userRegionCode = $user->assigned_regions;

            // Check if this page is available in the user's region
            $isPageAvailableInUserRegion = true;
            $pageCountryCodes = $page->country_codes;

            if (!empty($pageCountryCodes) && is_array($pageCountryCodes) && count($pageCountryCodes) > 0) {
                // If page has specific country codes, check if user's region code is in the list
                $isPageAvailableInUserRegion = in_array($userRegionCode, $pageCountryCodes);
            } elseif (!empty($pageCountryCodes) && is_string($pageCountryCodes)) {
                 $isPageAvailableInUserRegion = strpos($pageCountryCodes, $userRegionCode) !== false;
            }
            // If page has no specific country codes (empty), it's available to all regions

            if (!$isPageAvailableInUserRegion) {
                abort(403, 'You do not have permission to delete this page.');
            }
        }

        $page->deleted_by = auth()->id();
        $page->save();
        $page->delete();

        return redirect()->back()->with('success', 'Page deleted successfully');
    }

    /**
     * Toggle the active status of the specified resource
     * 
     * @param Page $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleStatus(Page $page)
    {
        // Check if the current user is allowed to toggle the status of this page (based on region)
        $user = auth()->user();
        $isAdmin = $user->role == 1 || $user->hasRole('admin') || $user->hasRole('super admin');

        if (!$isAdmin) {
            // Only allow region users to toggle pages that belong to their region
            if (empty($user->assigned_regions)) {
                abort(403, 'You do not have a valid region assigned.');
            }

            $userRegionCode = $user->assigned_regions;
            
            // Check if page belongs to user region via country_codes
            $pageCountryCodes = $page->country_codes;
            $isPageInRegion = false;

            if(is_array($pageCountryCodes)) {
                $isPageInRegion = in_array($userRegionCode, $pageCountryCodes);
            } elseif (is_string($pageCountryCodes)) {
                $isPageInRegion = strpos($pageCountryCodes, $userRegionCode) !== false;
            }

            if (!$isPageInRegion) {
                 abort(403, 'You do not have permission to toggle this page.');
            }
        }

        $page->active = !$page->active;
        $page->save();

        return redirect()->back()->with('success', 'Page status updated successfully');
    }
}