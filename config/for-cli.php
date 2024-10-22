<?php

use function Env\env;

require_once('vendor/autoload.php');
$root_dir = dirname(__DIR__, 3);

$env_files = file_exists('.env.local')
    ? ['.env', '.env.local']
    : ['.env'];

$dotenv = Dotenv\Dotenv::createUnsafeImmutable('.', $env_files, false);
if (file_exists('.env')) {
    $dotenv->load();
    $dotenv->required(['WP_HOME', 'WP_SITEURL']);
    if (!env('DATABASE_URL')) {
        $dotenv->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
    }
}
const WP_USE_EXT_MYSQL = true;

require_once(dirname(__FILE__, 2) . '/src/Patterns/DbSingleton.php');
require_once(dirname(__FILE__, 2).'/public_html/app/doctrine4wp/class-wpdb.php');

$hostForDoctrine = substr(
    env('DB_HOST'),
    0,
    strpos(env('DB_HOST'), ':')
);

if ($hostForDoctrine === '') {
    $hostForDoctrine = $GLOBALS['_ENV']['DB_HOST'];
}

$params = [
    'db' => env('DB_NAME'),
    'password' => env('DB_PASSWORD'),
    'user' => env('DB_USER'),
    'host' => $hostForDoctrine,
    'port' => env('DB_PORT') ?? '5432'
];

unset($hostForDoctrine);

try {
    $wpOrm = Src\WpDoctrine\WpOrm::get_instance();
    $wpOrm->init($params);

    return $wpOrm->getManager();
} catch (Throwable $e) {
    error_log($e->getMessage());
} //init doctrine 2
