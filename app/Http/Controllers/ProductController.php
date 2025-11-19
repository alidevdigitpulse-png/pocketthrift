<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function deleteImages(Request $request)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        $imagePath = $request->input('image_path');
        
        // Remove the image from storage if it exists
        if (Storage::exists($imagePath)) {
            Storage::delete($imagePath);
        }

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
}