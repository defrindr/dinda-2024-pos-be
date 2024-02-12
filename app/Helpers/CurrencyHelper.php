<?php

namespace App\Helpers;

class CurrencyHelper
{
    public static function rupiah(?int $number)
    {
        if (! $number) {
            return 'Rp. -';
        }
        $formatted = 'Rp '.number_format($number, 2, ',', '.');

        return $formatted;
    }
}
