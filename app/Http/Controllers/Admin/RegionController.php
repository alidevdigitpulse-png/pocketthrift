<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Region::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $query->where('country', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('code', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by active status
        if ($request->has('active') && $request->active != '') {
            $query->where('active', $request->active);
        }

        $regions = $query->orderBy('sort', 'asc')->orderBy('country', 'asc')->paginate(10);

        return view('admin.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('admin.regions.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'country' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:regions,code',
            'sort' => 'required|integer',
            'active' => 'boolean'
        ]);

        $region = new Region();
        $region->country = $request->country;
        $region->code = $request->code;
        $region->sort = $request->sort ?? 0;
        $region->active = $request->has('active') ? 1 : 0;
        $region->created_by = Auth::id();
        $region->save();

        return redirect()->route('admin.region.index')->with('success', 'Region created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function show(Region $region)
    {
        return view('admin.regions.show', compact('region'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function edit(Region $region)
    {
        $users = User::all();
        return view('admin.regions.edit', compact('region', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Region $region)
    {
        $request->validate([
            'country' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:regions,code,'.$region->id,
            'sort' => 'required|integer',
            'active' => 'boolean'
        ]);

        $region->country = $request->country;
        $region->code = $request->code;
        $region->sort = $request->sort ?? 0;
        $region->active = $request->has('active') ? 1 : 0;
        $region->updated_by = Auth::id();
        $region->save();

        return redirect()->route('admin.region.index')->with('success', 'Region updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Region  $region
     * @return \Illuminate\Http\Response
     */
    public function destroy(Region $region)
    {
        $region->deleted_by = Auth::id();
        $region->save();
        $region->delete();

        return redirect()->route('admin.region.index')->with('success', 'Region deleted successfully.');
    }
}