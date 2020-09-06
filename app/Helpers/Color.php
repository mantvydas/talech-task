<?php

namespace App\Helpers;

class Color
{
    protected static $colors = [
        'red' => 'Red',
        'dark_brown' => 'Dark Brown'
    ];

    /**
     * Get full array of colors
     *
     * @return array
     */
    public static function getColors(): array
    {
        return static::$colors;
    }

    /**
     * Get color by its code
     *
     * @param string $colorCode
     * @return string|null
     */
    public static function getColor(string $colorCode): ?string
    {
        return array_key_exists($colorCode, static::$colors) ? static::$colors[$colorCode] : null;
    }
}
