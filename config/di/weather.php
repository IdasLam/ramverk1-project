<?php

use \idla\Weather\Weather;

/**
 * Configuration file for request service.
 */
return [
    // Services to add to the container.
    "services" => [
        "weather" => [
            "shared" => true,
            "callback" => function () {
                return new Weather();
            }
        ],
    ],
];
