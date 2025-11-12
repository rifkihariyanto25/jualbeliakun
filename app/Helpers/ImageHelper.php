<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get proxied image URL for Wikia images
     * 
     * @param string|null $url
     * @param string $fallbackText
     * @return string
     */
    public static function getProxiedImage(?string $url, string $fallbackText = 'No Image'): string
    {
        if (empty($url)) {
            return self::getFallbackImage($fallbackText);
        }

        // If it's a Wikia image, try to fix the URL
        if (str_contains($url, 'wikia.nocookie.net') || str_contains($url, 'fandom.com')) {
            // Use images.weserv.nl as proxy to bypass referrer restrictions
            return 'https://images.weserv.nl/?url=' . urlencode($url);
        }

        return $url;
    }

    /**
     * Get fallback image using UI Avatars
     * 
     * @param string $text
     * @param int $size
     * @return string
     */
    public static function getFallbackImage(string $text, int $size = 300): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($text)
            . '&size=' . $size
            . '&background=random&color=fff&bold=true';
    }
}
