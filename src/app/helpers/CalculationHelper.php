<?php

namespace App\Helpers;

class CalculationHelper
{
    public static function convert($sum, $currency)
    {
        return ceil($sum / $currency);
    }

    public static function sale($sum, $sale)
    {
        return round( $sum - ($sum * ($sale / 100)));
    }
}