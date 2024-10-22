<?php
if (!defined('DOCTRINE4WP_ROOT')) {
    define('DOCTRINE4WP_DEBUG', false);
    define('DOCTRINE4WP_LOG_ERRORS', true);
    define('DOCTRINE4WP_INSECURE', false);

    if (file_exists(dirname(__FILE__) . '/doctrine4wp'))
        define('DOCTRINE4WP_ROOT', dirname(__FILE__) . '/doctrine4wp');
    else if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/app/plugins/doctrine4wp'))
        define('DOCTRINE4WP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/app/plugins/doctrine4wp');
    else if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/app/doctrine4wp'))
        define('DOCTRINE4WP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/app/pg4wp');
    else
        die('DOCTRINE4WP file directory not found');
    require_once(DOCTRINE4WP_ROOT . '/core.php');
} // Protection against multiple loading
