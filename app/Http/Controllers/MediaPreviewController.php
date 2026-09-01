<?php

namespace App\Http\Controllers;

use App\Support\PreviewMedia;
use Illuminate\Support\Facades\Storage;

class MediaPreviewController extends Controller
{
    private const PREVIEW_VERSION = 'v2';
    private const MAX_DIMENSION = 720;
    private const JPEG_QUALITY = 58;

    public function show(string $token)
    {
        $path = PreviewMedia::decode($token);

        abort_unless($path && Storage::disk('public')->exists($path), 404);
        abort_unless(PreviewMedia::isImage($path), 404);

        $disk = Storage::disk('public');
        $sourcePath = $disk->path($path);
        $cachePath = $this->previewCachePath($path, $sourcePath);

        if (! file_exists($cachePath)) {
            $this->generatePreview($sourcePath, $cachePath);
        }

        return response()->file($cachePath, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    protected function previewCachePath(string $path, string $sourcePath): string
    {
        $cacheDirectory = storage_path('app/public/preview-cache');
        if (! is_dir($cacheDirectory)) {
            mkdir($cacheDirectory, 0775, true);
        }

        $fingerprint = sha1($path.'|'.filemtime($sourcePath).'|'.filesize($sourcePath).'|'.self::PREVIEW_VERSION.'|'.self::MAX_DIMENSION.'|'.self::JPEG_QUALITY);

        return $cacheDirectory.DIRECTORY_SEPARATOR.$fingerprint.'.jpg';
    }

    protected function generatePreview(string $sourcePath, string $cachePath): void
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagejpeg')) {
            copy($sourcePath, $cachePath);
            return;
        }

        $imageInfo = @getimagesize($sourcePath);
        abort_unless($imageInfo, 404);

        [$width, $height] = $imageInfo;
        $maxDimension = self::MAX_DIMENSION;
        $scale = min($maxDimension / max($width, 1), $maxDimension / max($height, 1), 1);
        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));

        $sourceImage = match ($imageInfo[2] ?? null) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($sourcePath),
            default => false,
        };

        abort_unless($sourceImage, 404);

        $previewImage = imagecreatetruecolor($targetWidth, $targetHeight);
        $white = imagecolorallocate($previewImage, 255, 255, 255);
        imagefill($previewImage, 0, 0, $white);
        imagecopyresampled(
            $previewImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $width,
            $height
        );

        imagejpeg($previewImage, $cachePath, self::JPEG_QUALITY);

        imagedestroy($sourceImage);
        imagedestroy($previewImage);
    }
}
