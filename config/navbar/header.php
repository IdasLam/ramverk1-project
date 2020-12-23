<?php
/**
 * Supply the basis for the navbar as an array.
 */

$loggedin = $_SESSION['loggedin'] ?? null;
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
            "text" => "Posts",
            "url" => "post",
            "title" => "Latest posts",
        ],
        [
            "text" => "Tags",
            "url" => "tag",
            "title" => "tags",
        ],
        [
            "text" => "Om",
            "url" => "om",
            "title" => "Om denna webbplats.",
        ],
        [
            "text" => isset($loggedin) ? "Profile" : "Login",
            "url" => isset($loggedin) ? "profile" : "login",
            "title" => isset($loggedin) ? "profile" : "login",
        ]
    ],
];
