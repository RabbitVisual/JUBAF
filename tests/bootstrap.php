<?php

/**
 * Garante APP_ENV=testing e variáveis de BD antes do Dotenv, para carregar
 * `.env.testing` e não o `.env` local (evita nomes de BD legados).
 */
$_ENV['APP_ENV'] = 'testing';
$_SERVER['APP_ENV'] = 'testing';
putenv('APP_ENV=testing');

$testingDb = [
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'jubaf_test',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',
];

foreach ($testingDb as $key => $value) {
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
    putenv("{$key}={$value}");
}

require dirname(__DIR__).'/vendor/autoload.php';
