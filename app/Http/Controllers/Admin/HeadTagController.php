<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeadTag;
use Illuminate\Http\Request;

class HeadTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $headTags = HeadTag::where('title', 'LIKE', "%$keyword%")
                ->orWhere('code', 'LIKE', "%$keyword%")
                ->orderBy('id', 'desc')->paginate($perPage);
        } else {
            $headTags = HeadTag::orderBy('id', 'desc')->paginate($perPage);
        }

        return view('admin.headtags.index', compact('headTags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.headtags.create');
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
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'code' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $requestData = $request->all();
        
        HeadTag::create($requestData);

        return redirect('admin/head-tag')->with('flash_message', 'Head Tag added!');
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
        $headTag = HeadTag::findOrFail($id);

        return view('admin.headtags.show', compact('headTag'));
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
        $headTag = HeadTag::findOrFail($id);

        return view('admin.headtags.edit', compact('headTag'));
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
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'code' => 'required',
            'status' => 'required|in:0,1',
        ]);
        
        $requestData = $request->all();

        $headTag = HeadTag::findOrFail($id);
        $headTag->update($requestData);

        return redirect('admin/head-tag')->with('flash_message', 'Head Tag updated!');
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
        HeadTag::destroy($id);

        return redirect('admin/head-tag')->with('flash_message', 'Head Tag deleted!');
    }
}
