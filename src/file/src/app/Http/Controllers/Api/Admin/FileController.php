<?php

namespace Mangosteen\File\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

/**
 *
 */
class FileController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $file = $request->file('image');

        $image = Image::make($file)->encode('webp', 100);

        $webpPath = 'uploads/' . time() . '-' . uniqid() . '.webp';
        $image->save(public_path($webpPath));

        return response()->json([
            'webp_path' => asset($webpPath)
        ]);
    }
}
