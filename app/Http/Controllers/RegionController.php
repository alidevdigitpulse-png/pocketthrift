<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class RegionController extends Controller
{
    public function setRegionToUs(Request $request)
    {
        session(['current_region' => 'us']);
        session(['locale' => 'en-US']);
        \App::setLocale('en-US');

        // Always redirect to US homepage
        return redirect('/');
    }
}
