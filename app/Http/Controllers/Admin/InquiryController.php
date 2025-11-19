<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $data = Inquiry::whereType($request->get('inquiry'))->get();
        return view('admin.inquiries.index', compact('data'));
    }
    public function submit(Request $request)
    {
        $request->validate([
            'type' => 'required|string'
        ]);
        $data = $request->except(['_token', '_method', 'type']); // Convert array to JSON-encoded string
        if ($request->type == 'newsletter') {
            $request->validate([
                'email' => 'required|email'
            ]);
            if (Inquiry::where('data->email', $data['email'])->first()) {
                return response()->json(['status' => false, 'message' => 'Email already exists']);
            }
        }
        Inquiry::create([
            'type' => $request->type,
            'data' => json_encode($data)
        ]);

        return response()->json(['status' => true, 'message' => $request->type == 'newsletter' ? 'Successfully Subscribed' : 'We will contact you soon!']);
    }
    public function detail($id){
        $data = Inquiry::find($id);
        return view('admin.inquiries.details', compact('data'));
    }
}
