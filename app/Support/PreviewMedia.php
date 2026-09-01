<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PreviewMedia
{
    private const PREVIEW_VERSION = 'v2';

    public static function url(string $path): string
    {
        $path = trim($path);

        if ($path === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $path)) {
            return $path;
        }

        $relativePath = ltrim($path, '/');

        if (self::isImage($relativePath) && Storage::disk('public')->exists($relativePath)) {
            return route('media.preview', ['token' => self::encode($relativePath)]) . '?v=' . self::PREVIEW_VERSION;
        }

        return asset($relativePath);
    }

    public static function encode(string $path): string
    {
        return rtrim(strtr(base64_encode($path), '+/', '-_'), '=');
    }

    public static function decode(string $token): ?string
    {
        $decoded = base64_decode(strtr($token, '-_', '+/'), true);

        return $decoded === false || $decoded === '' ? null : ltrim($decoded, '/');
    }

    public static function isImage(string $path): bool
    {
        $extension = Str::lower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'], true);
    }
}
