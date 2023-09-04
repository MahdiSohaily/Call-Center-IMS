<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "callcenter";

try {
    // stablish database connection
    $connection = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    define('DB_CONNECTION', $connection); // Database Connection Global Instance
} catch (PDOException $th) {
    echo "Error: " . $th->getMessage();
}
