<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Liste toutes les images (protégé)
     */
    public function index(Request $request)
    {
        $images = Image::all()->map(function ($image) {
            return [
                'id'            => $image->id,
                'hash'          => $image->hash,
                'original_name' => $image->original_name,
                'categorie'     => $image->categorie,
                'url'           => $image->url, // URL publique avec hash
                'mime_type'     => $image->mime_type,
                'size'          => $image->size,
                'created_at'    => $image->created_at,
            ];
        });

        return response()->json([
            'data' => $images,
        ]);
    }

    /**
     * Liste groupée par catégorie
     */
    public function categories()
    {
        $categories = Image::select('categorie')
            ->distinct()
            ->pluck('categorie');

        $result = $categories->map(function ($categorie) {
            $images = Image::where('categorie', $categorie)->get()->map(function ($image) {
                return [
                    'hash'          => $image->hash,
                    'original_name' => $image->original_name,
                    'url'           => $image->url,
                ];
            });

            return [
                'categorie' => $categorie,
                'count'     => $images->count(),
                'images'    => $images,
            ];
        });

        return response()->json([
            'data' => $result,
        ]);
    }
}
