<?php

namespace App\Helpers;

class RateHelper
{
    public static function convert($sum, $currency)
    {
        return round($sum / $currency);
    }
}