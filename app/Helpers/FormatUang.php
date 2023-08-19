<?php

namespace App\Helpers;

class FormatUang
{
    public static function hapusFormat($number)
    {
        $replacement = "";
        $charactersToRemove = ["Rp", " ", ".", "\u{A0}"];
        $nominal = str_replace($charactersToRemove, $replacement, $number);
        return $nominal;
    }
}
