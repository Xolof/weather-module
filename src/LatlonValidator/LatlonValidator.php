<?php

namespace Anax\latlonValidator;

/**
 * A class to validate latitude and longitude coordinates.
 */
class LatlonValidator
{
    public function validateLatlon($coordinates)
    {
        // Regex for lat lon
        $latlonRe = "/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?),\s*[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/";

        if (preg_match($latlonRe, $coordinates)) {
            return true;
        }

        return false;
    }
}
