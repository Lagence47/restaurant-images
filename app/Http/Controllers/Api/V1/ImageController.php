<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $query = Image::with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('category_slug')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category_slug));
        }

        return $query->latest()->paginate(50);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'file'        => ['required', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
        ]);

        $file = $request->file('file');
        $hash = Image::hashFor($file);

        $existing = Image::where('hash', $hash)->first();
        if ($existing) {
            return response()->json([
                'message' => 'Image already exists (same content)',
                'image'   => $existing,
            ], 200);
        }

        $category = Category::findOrFail($request->category_id);
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $path = $file->storeAs(
            "images/{$category->slug}",
            "{$hash}.{$extension}",
            'public'
        );

        $image = Image::create([
            'category_id'  => $category->id,
            'hash'         => $hash,
            'original_name' => $file->getClientOriginalName(),
            'path'         => $path,
            'mime'         => $file->getMimeType(),
            'size'         => $file->getSize(),
        ]);

        return response()->json($image->load('category'), 201);
    }

    public function show(string $hash)
    {
        $image = Image::with('category')->where('hash', $hash)->firstOrFail();
        return $image;
    }

    public function destroy(Image $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return response()->noContent();
    }
}
