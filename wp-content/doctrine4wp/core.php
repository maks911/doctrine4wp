<?php
/**
 * @package PostgreSQL_For_Wordpress
 * @version $Id$
 * @author  GOSWEB
 */

/**
 * This file does all the initialisation tasks
 */

// Logs are put in the pg4wp directory
define('DOCTRINE4WP_LOG', DOCTRINE4WP_ROOT . '/logs/');
// Check if the logs directory is needed and exists or create it if possible
if ((DOCTRINE4WP_DEBUG || DOCTRINE4WP_LOG_ERRORS) &&
    !file_exists(DOCTRINE4WP_LOG) &&
    is_writable(dirname(DOCTRINE4WP_LOG)))
    mkdir(DOCTRINE4WP_LOG);

// Load the driver defined in 'db.php' + composer dependencies and new wpdb2 class
$params = [
    'db' => DB_NAME,
    'password' => DB_PASSWORD,
    'user' => DB_USER,
    'host' => DB_HOST,
    'port' => $GLOBALS['_ENV']['DB_PORT'] ?? '3306'
];

require_once(DOCTRINE4WP_ROOT . '/class-wpdb.php');
try {
    Src\WpDoctrine\WpOrm::get_instance()->init($params);

    if (!isset($wpdb)) {
        $wpdb = wpdb2::get_instance(Src\WpDoctrine\WpOrm::get_instance()->getConnection(), $params);
    }

} catch (\Doctrine\DBAL\Exception $e) {
    error_log($e->getMessage());
}