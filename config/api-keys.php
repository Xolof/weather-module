<?php
/**
 * Configuration file.
 */
if (getenv("MY_TRAVIS_ENV") || getenv("SCRUTINIZER")) {
    return [
        "geotag-key" => getenv("GEOTAG_KEY"),
        "weather-key" => getenv("WEATHER_KEY"),
    ];
}

return [
    "geotag-key" => file_get_contents(ANAX_INSTALL_PATH . "/data/GEOTAG_KEY"),
    "weather-key" => file_get_contents(ANAX_INSTALL_PATH . "/data/WEATHER_KEY"),
];
