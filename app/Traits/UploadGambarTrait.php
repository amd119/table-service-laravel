<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait UploadGambarTrait {
    public function uploadFile(Request $request, string $inputName, ?string $oldPath = null, string $path = 'uploads') 
    {
        // If no file was uploaded or file input is empty, keep old path or use default
        if(!$request->hasFile($inputName)) {
            Log::debug('No new file uploaded, keeping existing path: ' . ($oldPath ?? 'img/default.jpg'));
            return $oldPath ?? 'img/default.jpg';
        }

        // Check if uploaded file is valid
        if(!$request->file($inputName)->isValid()) {
            Log::debug('Uploaded file is invalid');
            return $oldPath ?? 'img/default.jpg';
        }
        
        Log::debug('Valid file uploaded: ' . $request->file($inputName)->getClientOriginalName());

        $validator = Validator::make(
            [$inputName => $request->file($inputName)],
            [$inputName => 'image|mimes:jpeg,png,jpg|max:1024']
        );

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $file = $request->file($inputName);
        $fileName = 'menu_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Delete old file if it exists and isn't default
        if ($oldPath && $oldPath != 'img/default.jpg') {
            // convert storage/uploads/filename.jpg to uploads/filename.jpg this is the relative path within the public disk.
            $oldStoragePath = str_replace('storage/', '', $oldPath);
            Log::debug('Attempting to delete old file: ' . $oldStoragePath);
            // because laravel tries to find the file in the storage/app/public/ and added with the relative path that has been converted to be like this -> storage/app/public/uploads/filename.jpg

            // check if file exists in the "public" disk at that relative path and delete it if exists
            if (Storage::disk('public')->exists($oldStoragePath)) {
                Storage::disk('public')->delete($oldStoragePath);
                Log::debug('Old file deleted successfully' . $oldStoragePath);
            } else {
                Log::debug('Old file not found for deletion' . $oldStoragePath);
            }
        }

        // Store using storeAs with explicit disk specification
        $file->storeAs($path, $fileName, 'public');
        Log::debug('New file stored: storage/' . $path . '/' . $fileName);
        
        // Return the public-accessible path
        return "storage/{$path}/{$fileName}";

    }
}