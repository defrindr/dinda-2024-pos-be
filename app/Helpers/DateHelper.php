<?php

namespace App\Helpers;

class DateHelper
{
    const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    public static function readableDate(string $rawDate)
    {
        $date = date('d-m-Y', strtotime($rawDate));

        $dates = explode('-', $date);

        $month = self::MONTHS[intval($dates[1]) - 1];

        $date = "{$dates[0]} {$month} {$dates[2]}";

        return $date;
    }
}
