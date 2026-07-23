<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('image_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('image_id')->constrained('images')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('media_type')->default('image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('images')
            ->select('id', 'file_path', 'created_at', 'updated_at')
            ->orderBy('id')
            ->chunkById(100, function ($images) {
                foreach ($images as $image) {
                    DB::table('image_media')->insert([
                        'image_id' => $image->id,
                        'file_path' => $image->file_path,
                        'media_type' => 'image',
                        'sort_order' => 0,
                        'created_at' => $image->created_at,
                        'updated_at' => $image->updated_at,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_media');
    }
};
