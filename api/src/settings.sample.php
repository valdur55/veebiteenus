<?php
return [
    "settings" => [
        "displayErrorDetails" => true, // set to false in production

        // Monolog settings
        "logger" => [
            "name" => "veebiteenus",
            "path" => "/tmp/veebiteenus_api.log",
        ],
        "db" => [
            "host" => "localhost",
            "user" => "khk",
            "pass" => "khk",
            "options" => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            ),
            "dbname" => "khk_veebiteenus"
        ]
    ],
];
