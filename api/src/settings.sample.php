<?php
return [
    "settings" => [
        "displayErrorDetails" => true, // set to false in production

        // Monolog settings
        "logger" => [
            "name" => "slim-app",
            "path" => "/tmp/smsinc_api.log",
        ],
        "db" => [
            "host" => "localhost",
            "user" => "smsinc",
            "pass" => "",
            "options" => array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            ),
            "dbname" => "smsinc"
        ]
    ],
];
