<?php

namespace App\DeathStar\LanguageConverters;

class GalacticBasic implements LanguageConverterInterface
{
    public function convertDroidSpeak(string $droidSpeak)
    {
        $splitString = explode(' ', $droidSpeak);

        $splitString = array_map(function($binaryLetter) {
            return chr(bindec($binaryLetter));
        }, $splitString);

        return implode('', $splitString);
    }
}
