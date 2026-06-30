<?php
// Core application settings for SolarConnect.

if (!function_exists('solarconnect_load_env_file')) {
    function solarconnect_load_env_file(string $path): void
    {
        if (!is_file($path) || !is_readable($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) {
                continue;
            }

            $name = trim($parts[0]);
            $value = trim($parts[1]);

            if ($name === '') {
                continue;
            }

            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }

            if (getenv($name) === false && !array_key_exists($name, $_ENV)) {
                putenv($name . '=' . $value);
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

if (!function_exists('solarconnect_env')) {
    function solarconnect_env(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);

        if ($value !== false && $value !== '') {
            return $value;
        }

        if (array_key_exists($key, $_ENV) && $_ENV[$key] !== '') {
            return (string) $_ENV[$key];
        }

        if (array_key_exists($key, $_SERVER) && $_SERVER[$key] !== '') {
            return (string) $_SERVER[$key];
        }

        return $default;
    }
}

solarconnect_load_env_file(__DIR__ . '/../.env');

define('SITE_NAME', 'SolarConnect');

// XAMPP defaults work out of the box, while Render can override these via env vars.
define('DB_HOST', solarconnect_env('DB_HOST', '127.0.0.1'));
define('DB_NAME', solarconnect_env('DB_NAME', 'solarconnect'));
define('DB_USER', solarconnect_env('DB_USER', 'root'));
define('DB_PASS', solarconnect_env('DB_PASS', ''));
define('DB_PORT', (int) solarconnect_env('DB_PORT', '3306'));

// Default admin login for local development. Override in Render or .env when deploying.
define('ADMIN_USERNAME', solarconnect_env('ADMIN_USERNAME', 'admin'));
define('ADMIN_PASSWORD_HASH', solarconnect_env('ADMIN_PASSWORD_HASH', '$2b$12$nPMPqfY9iUEXOf.m3Wpq/ulEPp/EzdMJVdq/M6Xuzd1He/yJSp5IG'));
