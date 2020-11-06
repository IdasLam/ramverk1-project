<?php
/**
 * Flat file controller for reading markdown files from content/.
 */
return [
    "routes" => [
        [
            "info" => "Flat file content controller.",
            "mount" => "ip-validator",
            "handler" => "\Anax\Controller\IPAPIController",
        ],
    ]
];
