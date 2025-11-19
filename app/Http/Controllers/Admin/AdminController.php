<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Page;
use App\Models\Inquiry;

class AdminController extends Controller
{
    public function index()
    {
        $banner = Banner::all();
        $page = Page::all();
        $inquiry = Inquiry::all();

        return view('admin.dashboard', compact('banner', 'page', 'inquiry'));
    }
    public function profile()
    {
        return view('admin.profile');
    } public function settings()
    {
        return view('admin.settings');
    }


}
