<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\CreateRequest;
use App\Models\Image;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        try {
            $uploadedFile = $request->file('image');
            
            if (!$uploadedFile) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file was uploaded'
                ], 400);
            }
            
            // Upload image to Cloudinary
            $result = Cloudinary::upload($uploadedFile->getRealPath());
            
            // Validate Cloudinary response
            if (!$result || !$result->getPublicId() || !$result->getSecurePath()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image to Cloudinary'
                ], 500);
            }
            
            // Save image details to database
            $image = new Image();
            $image->public_id = $result->getPublicId();
            $image->file_name = $uploadedFile->getClientOriginalName();
            $image->file_type = $uploadedFile->getClientMimeType();
            $image->url = $result->getSecurePath(); // Use secure URL as primary URL
            $image->secure_url = $result->getSecurePath();
            $image->size = $result->getSize();
            // Set default values for polymorphic relationship
            $image->imageable_type = 'App\\Models\\User'; // Default to User model
            $image->imageable_id = 1; // Default to first user
            $image->save();

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'data' => $image
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}