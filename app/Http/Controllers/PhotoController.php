<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Tampilkan semua foto untuk produk tertentu.
     */
    public function index($productId)
    {
        $photos = Photo::where('product_id', $productId)->get();
        return view('photos.index', compact('photos', 'productId'));
    }

    /**
     * Simpan foto untuk produk tertentu.
     */
    public function store(Request $request, $productId)
    {
        $request->validate([
            'productPhotos' => 'required|array|max:9',
            'productPhotos.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $existingPhotosCount = Photo::where('product_id', $productId)->count();

        if ($existingPhotosCount + count($request->file('productPhotos')) > 9) {
            return redirect()->back()->withErrors('Total foto untuk produk ini tidak boleh lebih dari 9.');
        }

        foreach ($request->file('productPhotos') as $file) {
            $path = $file->store("products/{$productId}/photos", 'public');
            Photo::create([
                'url' => Storage::url($path),
                'product_id' => $productId,
            ]);
        }

        return redirect()->route('photos.index', $productId)->with('success', 'Foto berhasil ditambahkan.');
    }

    /**
     * Perbarui foto.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $photo = Photo::findOrFail($id);

        // Hapus foto lama
        $oldPath = str_replace('/storage/', '', $photo->url);
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Simpan foto baru
        $path = $request->file('photo')->store("products/{$photo->product_id}/photos", 'public');
        $photo->update([
            'url' => Storage::url($path),
        ]);

        return redirect()->back()->with('success', 'Foto berhasil diperbarui.');
    }

    /**
     * Hapus foto.
     */
    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);

        // Hapus file dari storage
        $path = str_replace('/storage/', '', $photo->url);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $photo->delete();

        return redirect()->back()->with('success', 'Foto berhasil dihapus.');
    }
}
