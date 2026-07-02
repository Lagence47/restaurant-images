<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends Controller
{
    /**
     * Génère un slug propre à partir d'un nom de fichier
     */
    private function generateSlug(string $filename): string
    {
        // Enlever l'extension
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        // Slugifier : lowercase, remplacer espaces/virgules par tirets, enlever caractères spéciaux
        $slug = Str::slug($name, '-');

        return $slug . '.' . strtolower($extension);
    }

    /**
     * Liste toutes les catégories disponibles
     */
    public function categories()
    {
        $directories = Storage::disk('public')->directories('images/plats');

        $categories = collect($directories)->map(function ($dir) {
            return basename($dir);
        });

        return response()->json([
            'data' => $categories,
        ]);
    }

    /**
     * Liste les images d'une catégorie spécifique
     */
    public function index(string $categorie)
    {
        $folder = "images/plats/{$categorie}";

        abort_unless(
            Storage::disk('public')->exists($folder),
            404,
            'Catégorie introuvable'
        );

        $files = Storage::disk('public')->files($folder);

        $images = collect($files)->map(function ($path) use ($categorie) {
            $originalName = basename($path);
            $slug = $this->generateSlug($originalName);

            return [
                'name'          => $originalName,
                'slug'          => $slug, // ✅ Nom propre pour l'API
                'categorie'     => $categorie,
                'url'           => asset('storage/' . $path),
                'url_slug'      => url("/api/v1/images/{$categorie}/{$slug}"), // ✅ URL propre
                'path'          => $path,
                'size'          => Storage::disk('public')->size($path),
            ];
        });

        return response()->json([
            'data' => $images,
        ]);
    }

    /**
     * Retourne une image spécifique (par slug ou nom original)
     */
    public function show(string $hash): BinaryFileResponse
    {
        $image = Image::where('hash', $hash)->firstOrFail();

        $fullPath = Storage::disk('public')->path($image->path);

        abort_unless(
            file_exists($fullPath),
            404,
            'Image introuvable'
        );

        return response()->file($fullPath, [
            'Content-Type'  => $image->mime_type,
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
