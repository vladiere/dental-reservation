<?php

namespace App\Support;

use Illuminate\Support\Str;

class Capitalization
{
    public static function capitalize(string $inputs, string $separator): string
    {
        // Split the input string by the provided separator
        $words = explode($separator, $inputs);

        // Capitalize each word and combine them back with the separator
        $result = array_map(function ($word) {
            return Str::of($word)->ucfirst();
        }, $words);

        // Join the capitalized words with a space as separator
        return implode(" ", $result);
    }
}
