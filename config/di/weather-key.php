<?php
/**
 * Configuration file for Geotag IP API.
 */
return [
    // Services to add to the container.
    "services" => [
        "weather-key" => [
            "shared" => true,
            "callback" => function () {
                $keyHolder = new \Anax\KeyHolder\KeyHolder();

                // Load the configuration files
                $cfg = $this->get("configuration");
                $config = $cfg->load("api-keys.php");

                $key = $config["config"]["weather-key"];

                $keyHolder->setKey($key);

                return $keyHolder;
            }
        ],
    ],
];
