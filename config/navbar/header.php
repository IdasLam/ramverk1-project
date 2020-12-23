<?php
/**
 * Supply the basis for the navbar as an array.
 */

$loggedin = $_SESSION['loggedin'] ?? null;
$username = $_SESSION['username'] ?? null;
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",
 
    // Here comes the menu items
    "items" => [
        [
            "text" => "Home",
            "url" => "home",
            "title" => "Home",
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
            "text" => "About",
            "url" => "om",
            "title" => "About",
        ],
        [
            "text" => isset($loggedin) ? "Profile" : "Login",
            "url" => isset($loggedin) ? "profile?user=" . $username : "login",
            "title" => isset($loggedin) ? "profile" : "login",
        ]
    ],
];
