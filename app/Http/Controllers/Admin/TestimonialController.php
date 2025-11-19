<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Schema;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use File;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{

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
        $data = new Testimonial();

        if($search != null){
            $query = Testimonial::query();

            $table = $data->getTable();

            $columns = $this->removeColumns(Schema::getColumnListing($table), ['created_at', 'updated_at', 'image', 'id']);

            foreach($columns as $column){
                $query->orWhere($column, 'LIKE', '%' . $search . '%');
            }
            $data = $query->orderBy('name')->paginate(12);

            if($request->onChange == true)
            {
                return response()->json(['status' => true, 'data' => $data,'lastPage' => $data->lastPage()]);
            }

        }
        else{
            $data = $data->paginate(12);
            if ($request->onChange == true) {
                return response()->json(['status' => true, 'data' => $data, 'lastPage' => $data->lastPage()]);
            }
        }


        return view('admin.testimonial.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $data = null;
        return view('admin.testimonial.create', compact('data'));
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
			'name' => 'required',
		]);
        $request->request->remove('_token');
        $data = $request->input();
        if ($request->hasFile('image')) {
            File::isDirectory(public_path('uploads/testimonial')) or File::makeDirectory(public_path('uploads/testimonial'), 0777, true, true);

            $fileName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/testimonial'), $fileName);
            $data['image'] = 'uploads/testimonial/'.$fileName;
        }
        Testimonial::create($data);

        return redirect('admin/admin/testimonial')->with('success', 'Testimonial added!');
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
        $data = Testimonial::findOrFail($id);

        return view('admin.testimonial.create', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $this->validate($request, [
			'name' => 'required',
		]);
        $request->request->remove('_token');
        $request->request->remove('_method');
        $data = $request->input();
        if ($request->hasFile('image')) {
            File::isDirectory(public_path('uploads/testimonial')) or File::makeDirectory(public_path('uploads/testimonial'), 0777, true, true);
            if (File::exists(public_path($testimonial->image))) {
                File::delete(public_path($testimonial->image));
            }
            $fileName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/testimonial'), $fileName);
            $data['image'] = 'uploads/testimonial/'.$fileName;
        }
        $testimonial->update($data);
        return redirect()->back()->with('success', 'Testimonial Updated');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Testimonial $testimonial)
    {

        $status = $testimonial->status;
        if($status == 0){
            $testimonial->status = 1;
            $message = 'Deactive';
        }else{
            $testimonial->status = 0;
            $message = 'Active';
        }
        $testimonial->save();

        return redirect()->back()->with('success', 'Testimonial '.$message);

    }
}
