<?php

namespace App\Console\Commands;

use App\Models\Image;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScanImages extends Command
{
    protected $signature = 'images:scan';
    protected $description = 'Scanner les dossiers d\'images et remplir la base de données';

    public function handle(): int
    {
        $this->info('Scan des images en cours...');

        $categories = Storage::disk('public')->directories('images/plats');

        foreach ($categories as $categoryPath) {
            $categorie = basename($categoryPath);
            $files = Storage::disk('public')->files($categoryPath);

            foreach ($files as $path) {
                $originalName = basename($path);
                $fullPath = Storage::disk('public')->path($path);

                // ✅ Hash déterministe basé sur le chemin
                $hash = substr(md5($path), 0, 12);

                $mimeType = mime_content_type($fullPath);
                $size = Storage::disk('public')->size($path);

                Image::updateOrCreate(
                    ['path' => $path],
                    [
                        'hash' => $hash,
                        'original_name' => $originalName,
                        'categorie' => $categorie,
                        'mime_type' => $mimeType,
                        'size' => $size,
                    ]
                );

                $this->line("✓ {$categorie}/{$originalName} → {$hash}");
            }
        }

        $this->info('Scan terminé !');
        return 0;
    }
}
