<?php

function env($key, $default = null) {
    static $envVars = null;

    if ($envVars === null) {
        $envFile = dirname(__DIR__) . '/.env';
        $envVars = [];

        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#')) {
                    continue;
                }
                [$name, $value] = explode('=', $line, 2);
                $envVars[trim($name)] = trim(trim($value), "\"'");
            }
        }
    }

    return $envVars[$key] ?? $default;
}

return [
    'name' => env('APP_NAME', 'Job Portal'),
    'env'  => env('APP_ENV', 'local'),
    'url'  => env('APP_URL', 'http://localhost/job-portal/public'),
];