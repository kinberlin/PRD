<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

if (!function_exists('formatNumber')) {
    function formatNumber($number)
    {
        if ($number >= 1000) {
            return number_format($number / 1000, 1) . 'K';
        }

        return (string)$number;
    }
}
if (!function_exists('formatDateInFrench')) {
    /**
     * Format a given date in French.
     *
     * @param string $date
     * @param string $formatKey
     * @return string
     */
    function formatDateInFrench($date, $formatKey = 'long')
    {
        $formats = Lang::get('dates');
        if (!is_array($formats)) {
            dd($formats); // Debug to see what is actually returned
        }
        $formats = trans('dates');

        if (!is_array($formats)) {
            throw new InvalidArgumentException('The date formats must be an array.');
        }

        if (!array_key_exists($formatKey, $formats)) {
            throw new InvalidArgumentException("The format key '$formatKey' does not exist.");
        }

        $format = $formats[$formatKey];
        return Carbon::parse($date)->translatedFormat($format);
    }
}
