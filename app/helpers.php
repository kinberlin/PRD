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
    {$dateFormats = [
        'short' => 'd/m/Y',         // Short format: 10/07/2024
        'long' => 'j F Y',          // Long format: 10 juillet 2024
        'day_month' => 'j F',       // Day and Month: 10 juillet
        'month_year' => 'F Y',      // Month and Year: juillet 2024
        'time' => 'H:i',            // Time format: 14:30
        'complete' => 'l j F Y',    // Complete format: mercredi 10 juillet 2024
        'full' => 'l j F Y H:i:s',  // Full format: mercredi 10 juillet 2024 14:30:00
    ];
        // Retrieve the date format from the predefined array
        $format = $dateFormats[$formatKey] ?? null;

        // Check if the format exists
        if (empty($format)) {
            throw new InvalidArgumentException("The format key '$formatKey' does not exist.");
        }

        // Return the formatted date
        return Carbon::parse($date)->translatedFormat($format);
    }
}
