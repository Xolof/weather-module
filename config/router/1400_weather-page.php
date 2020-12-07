<?php
/**
 * Routes to ease testing.
 */
return [

    // All routes in order
    "routes" => [
        [
            "info" => "Validate IP address.",
            "mount" => "weather",
            "handler" => "\Xolof\WeatherModule\WeatherController",
        ],
    ]
];
