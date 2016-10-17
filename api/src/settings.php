<?php
return [
    "settings" => [
        "displayErrorDetails" => true, // set to false in production


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
