<?php
// DIC configuration

$container = $app->getContainer();

// monolog
$container["logger"] = function ($c) {
    $settings = $c->get("settings")["logger"];
    $logger = new Monolog\Logger($settings["name"]);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings["path"], Monolog\Logger::DEBUG));
    return $logger;
};

// Database
$container["db"] = function ($c) {
    $db = $c["settings"]["db"];
    $pdo = new \Slim\PDO\Database("mysql:host=" . $db["host"] . ";dbname=" . $db["dbname"], $db["user"], $db["pass"], $db["options"]);
    return $pdo;
};

