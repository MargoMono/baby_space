<?php

namespace App\Helpers;

class CalculationHelper
{
    public static function convert($sum, $currency)
    {
        if (empty($currency)) {
            return null;
        }

        return ceil($sum / $currency);
    }

    public static function sale($sum, $sale)
    {
        if (empty($sale)) {
            return null;
        }

        return round($sum - ($sum * ($sale / 100)));
    }
}