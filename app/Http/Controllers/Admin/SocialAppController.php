<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialApp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SocialAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = SocialApp::with(['creator']);

        // Apply search filter if present
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $socialApps = $query->orderBy('sort', 'asc')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.social-apps.index', compact('socialApps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.social-apps.create');
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
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'sort' => 'integer|min:0'
        ]);

        $socialApp = new SocialApp();
        $socialApp->title = $request->title;
        $socialApp->sort = $request->sort ?? 0;
        $socialApp->created_by = Auth::id();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/store_icons'), $logoName);
            $socialApp->logo = 'uploads/store_icons/' . $logoName;
        }

        $socialApp->save();

        return redirect()->route('admin.social-app.index')->with('success', 'Social App created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SocialApp  $socialApp
     * @return \Illuminate\Http\Response
     */
    public function show(SocialApp $socialApp)
    {
        $socialApp->load(['creator', 'updater', 'deleter']);
        return view('admin.social-apps.show', compact('socialApp'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SocialApp  $socialApp
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialApp $socialApp)
    {
        return view('admin.social-apps.edit', compact('socialApp'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SocialApp  $socialApp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SocialApp $socialApp)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'sort' => 'integer|min:0'
        ]);

        $socialApp->title = $request->title;
        $socialApp->sort = $request->sort ?? 0;
        $socialApp->updated_by = Auth::id();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($socialApp->logo && file_exists(public_path($socialApp->logo))) {
                unlink(public_path($socialApp->logo));
            }

            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('uploads/store_icons'), $logoName);
            $socialApp->logo = 'uploads/store_icons/' . $logoName;
        }

        $socialApp->save();

        return redirect()->route('admin.social-app.index')->with('success', 'Social App updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SocialApp  $socialApp
     * @return \Illuminate\Http\Response
     */
    public function destroy(SocialApp $socialApp)
    {
        $socialApp->deleted_by = Auth::id();
        $socialApp->save();
        $socialApp->delete();

        return redirect()->route('admin.social-app.index')->with('success', 'Social App deleted successfully.');
    }
}
