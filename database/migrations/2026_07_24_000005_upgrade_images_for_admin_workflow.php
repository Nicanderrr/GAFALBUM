<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->string('status')->default('published')->after('description');
            $table->foreignId('cover_media_id')->nullable()->after('thumbnail_path')->constrained('image_media')->nullOnDelete();
            $table->timestamp('published_at')->nullable()->after('cover_media_id');
        });

        DB::table('images')->orderBy('id')->chunkById(100, function ($images) {
            foreach ($images as $image) {
                $coverMediaId = DB::table('image_media')
                    ->where('image_id', $image->id)
                    ->where('media_type', 'image')
                    ->orderBy('sort_order')
                    ->value('id');

                DB::table('images')
                    ->where('id', $image->id)
                    ->update([
                        'status' => 'published',
                        'cover_media_id' => $coverMediaId,
                        'published_at' => $image->created_at,
                    ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cover_media_id');
            $table->dropColumn(['status', 'published_at']);
        });
    }
};
