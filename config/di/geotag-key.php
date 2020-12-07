<?php
/**
 * Configuration file for Geotag IP API.
 */
return [
    // Services to add to the container.
    "services" => [
        "geotag-key" => [
            "shared" => true,
            "callback" => function () {
                $keyHolder = new \Xolof\WeatherModule\KeyHolder();

                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("api-keys.php");

                $key = $config["config"]["geotag-key"];

                $keyHolder->setKey($key);

                return $keyHolder;
            }
        ],
    ],
];
