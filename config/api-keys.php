<?php
/**
 * Configuration file.
 */
return [
    "geotag-key" => file_get_contents(ANAX_INSTALL_PATH . "/data/GEOTAG_KEY"),
    "weather-key" => file_get_contents(ANAX_INSTALL_PATH . "/data/WEATHER_KEY"),
];
