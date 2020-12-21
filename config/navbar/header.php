<?php
/**
 * Supply the basis for the navbar as an array.
 */

$loggedin = $_SESSION['loggedin'];
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",
 
    // Here comes the menu items
    "items" => [
        [
            "text" => "Hem",
            "url" => "home",
            "title" => "Första sidan, börja här.",
        ],
        [
            "text" => "Om",
            "url" => "om",
            "title" => "Om denna webbplats.",
        ],
        // [
        //     "text" => "IP-validator",
        //     "url" => "ip",
        //     "title" => "IP-validator.",
        // ]
        [
            "text" => $loggedin ? "Profile" : "Login",
            "url" => $loggedin ? "profile" : "login",
            "title" => $loggedin ? "profile" : "login",
        ]
    ],
];
