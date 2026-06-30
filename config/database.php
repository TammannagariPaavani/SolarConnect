<?php
require_once __DIR__ . '/config.php';

function db_connect(): mysqli
{
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    mysqli_report(MYSQLI_REPORT_OFF);
    $connection = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

    if ($connection->connect_error) {
        throw new RuntimeException('Database connection failed.');
    }

    $connection->set_charset('utf8mb4');

    return $connection;
}
