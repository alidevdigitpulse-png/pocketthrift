<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RegionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class RegionController extends Controller
{
    protected $regionService;

    public function __construct(RegionService $regionService)
    {
        $this->regionService = $regionService;
    }

    public function setRegion(Request $request)
    {
        $request->validate([
            'region' => 'required|string|exists:regions,code,active,1'
        ]);

        $regionCode = $request->input('region');
        
        // Set the region in session
        $this->regionService->setCurrentRegion($regionCode);
        
        return response()->json([
            'success' => true,
            'message' => 'Region updated successfully',
            'region' => $regionCode
        ]);
    }
}