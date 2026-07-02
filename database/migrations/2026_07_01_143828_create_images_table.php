<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique(); // Hash pour l'URL
            $table->string('original_name'); // Nom original du fichier
            $table->string('categorie'); // burgers, pizzas, etc.
            $table->string('path'); // Chemin complet dans storage
            $table->string('mime_type'); // image/jpeg, image/png, etc.
            $table->unsignedBigInteger('size'); // Taille en bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
