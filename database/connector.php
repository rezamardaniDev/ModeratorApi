<?php

global $db;

$hostname = 'localhost';
$username = 'faraitir_root';
$password = 'mardani80';
$database = 'faraitir_moderator';

try {
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $db = new PDO("mysql:host={$hostname};dbname={$database};charset=UTF8", $username, $password, $options);
    echo "<center>Database Connected!</center>";
} catch (PDOException $e) {
    echo $e->getMessage();
}
