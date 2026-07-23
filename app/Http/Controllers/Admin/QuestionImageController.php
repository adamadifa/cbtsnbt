<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuestionImageController extends Controller
{
    /**
     * Handle the image upload from CKEditor SimpleUploadAdapter
     */
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            
            // Validate file size and type
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5MB
            ]);

            $extension = $file->getClientOriginalExtension();
            $filename = Str::random(40) . '.' . $extension;
            
            // Store in public/questions/editor/
            $path = $file->storeAs('questions/editor', $filename, 'public');
            $url = Storage::url($path);

            return response()->json([
                'url' => $url,
                'location' => $url
            ]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
