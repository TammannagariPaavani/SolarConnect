<?php
// Shared bootstrap for every public/admin page.

$sessionPath = __DIR__ . '/../tmp/sessions';

if (!is_dir($sessionPath)) {
    @mkdir($sessionPath, 0777, true);
}

if (is_dir($sessionPath) && is_writable($sessionPath)) {
    session_save_path($sessionPath);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';
