<?php

namespace App\Services;

class SmsService
{
    /**
     * Get the list of operator prefixes.
     *
     * @return array
     */
    public static function getOperatorPrefixes()
    {
        return ['99450', '99451', '99455', '99470', '99477'];
    }

    /**
     * Get the list of countries with their calling codes.
     *
     * @return array
     */
    public static function getCountryList()
    {
        return [
            1 => 'United States',
            994 => 'Azerbaijan',
            // Add more countries here as needed
        ];
    }

    /**
     * Convert a full prefix to a 3-digit prefix.
     *
     * @param string $prefix
     * @return string
     */
    public static function get3DigitPrefix($prefix)
    {
        return '0' . substr($prefix, 3, 2);
    }
}