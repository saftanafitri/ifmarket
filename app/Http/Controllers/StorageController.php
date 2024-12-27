<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class StorageController extends Controller
{
    public function showPrivateFile($path)
    {
        // Tentukan path file yang berada di storage/app/private
        $fullPath = storage_path('app/private/' . $path);
        
        // Pastikan file ada
        if (file_exists($fullPath)) {
            return response()->file($fullPath);
        } else {
            abort(404); // Jika file tidak ditemukan
        }
    }
}
