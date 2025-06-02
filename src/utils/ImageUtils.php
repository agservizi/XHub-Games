<?php
/**
 * Image Utility Class for Xbox Games Catalog
 * Handles cover image validation, fallbacks, and optimization
 */

class ImageUtils {
    
    // Default fallback image for games without cover
    const DEFAULT_COVER = '/public/assets/images/default-game-cover.png';
    
    // Xbox-style placeholder SVG
    const XBOX_PLACEHOLDER = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjQwMCIgdmlld0JveD0iMCAwIDMwMCA0MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iNDAwIiBmaWxsPSIjMUYxRjFGIi8+CjxjaXJjbGUgY3g9IjE1MCIgY3k9IjE4MCIgcj0iNDAiIGZpbGw9IiMxMDdDMTAiLz4KPHRleHQgeD0iMTUwIiB5PSIyNjAiIGZpbGw9IiNmZmZmZmYiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxOCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+R2FtZSBDb3ZlcjwvdGV4dD4KPHRleHQgeD0iMTUwIiB5PSIyODAiIGZpbGw9IiM5OTk5OTkiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+Tm90IEF2YWlsYWJsZTwvdGV4dD4KPC9zdmc+';

    /**
     * Validate if image URL is accessible
     */
    public static function validateImageUrl($url) {
        if (empty($url)) {
            return false;
        }

        // Check if URL is valid
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Check if image is accessible (basic check)
        $headers = @get_headers($url, 1);
        if (!$headers) {
            return false;
        }

        $httpCode = substr($headers[0], 9, 3);
        return $httpCode == '200';
    }

    /**
     * Get optimized image URL or fallback
     */
    public static function getGameCoverUrl($coverUrl, $size = 'medium') {
        // If no cover provided, return Xbox placeholder
        if (empty($coverUrl)) {
            return self::XBOX_PLACEHOLDER;
        }

        // If it's already our placeholder, return it
        if (strpos($coverUrl, 'data:image/svg+xml') === 0) {
            return $coverUrl;
        }

        // For IGDB images, optimize size
        if (strpos($coverUrl, 'images.igdb.com') !== false) {
            // Replace size parameter
            $sizeMap = [
                'small' => 't_cover_small',
                'medium' => 't_cover_big', 
                'large' => 't_cover_big_2x'
            ];
            
            $newSize = $sizeMap[$size] ?? $sizeMap['medium'];
            $coverUrl = preg_replace('/t_cover_[a-z_0-9]+/', $newSize, $coverUrl);
        }

        return $coverUrl;
    }

    /**
     * Generate responsive image HTML with fallback
     */
    public static function generateGameCoverHtml($game, $classes = '', $lazy = true) {
        $coverUrl = self::getGameCoverUrl($game['cover_url']);
        $title = htmlspecialchars($game['title']);
        
        $lazyAttr = $lazy ? 'loading="lazy"' : '';
        $placeholderClass = $lazy ? 'lazy-load' : '';
        
        return sprintf(
            '<img src="%s" alt="Copertina di %s" class="game-cover %s %s" %s onerror="this.src=\'%s\'">',
            $coverUrl,
            $title,
            $classes,
            $placeholderClass,
            $lazyAttr,
            self::XBOX_PLACEHOLDER
        );
    }

    /**
     * Download and save cover image locally (for offline use)
     */
    public static function downloadCover($url, $gameId) {
        if (!self::validateImageUrl($url)) {
            return false;
        }

        $uploadDir = __DIR__ . '/../../public/assets/images/covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (!$extension) {
            $extension = 'jpg';
        }

        $filename = "game_{$gameId}.{$extension}";
        $filepath = $uploadDir . $filename;

        $imageData = @file_get_contents($url);
        if ($imageData !== false) {
            file_put_contents($filepath, $imageData);
            return "/public/assets/images/covers/{$filename}";
        }

        return false;
    }

    /**
     * Generate Xbox-style loading placeholder
     */
    public static function getLoadingPlaceholder() {
        return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjQwMCIgdmlld0JveD0iMCAwIDMwMCA0MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iNDAwIiBmaWxsPSIjMUYxRjFGIi8+CjxjaXJjbGUgY3g9IjE1MCIgY3k9IjIwMCIgcj0iMjAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzEwN0MxMCIgc3Ryb2tlLXdpZHRoPSIzIj4KPGFudHJpYnV0ZSBuYW1lPSJyIiB2YWx1ZXM9IjE1OzI1OzE1IiBkdXI9IjJzIiByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIvPgo8L2NpcmNsZT4KPHRleHQgeD0iMTUwIiB5PSIyNjAiIGZpbGw9IiM5OTk5OTkiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgdGV4dC1hbmNob3I9Im1pZGRsZSI+TG9hZGluZy4uLjwvdGV4dD4KPC9zdmc+';
    }

    /**
     * Generate image metadata for SEO and accessibility
     */
    public static function getImageMetadata($game) {
        return [
            'alt' => "Copertina del gioco {$game['title']} ({$game['release_year']})",
            'title' => "{$game['title']} - {$game['genre']} - {$game['developer']}",
            'data-game-id' => $game['id'],
            'data-genre' => $game['genre'],
            'data-year' => $game['release_year']
        ];
    }
}
?>
