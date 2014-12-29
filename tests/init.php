<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

/**
 * Prepares a simple autoloader for the Capirussa\Eobot namespace
 */

date_default_timezone_set('Europe/Amsterdam');

require_once(dirname(__FILE__) . '/../vendor/autoload.php');

// handle autoloading
spl_autoload_register(
    function ($className) {
        // @codeCoverageIgnoreStart
        if ($className === 'MockEobotBrowser') {
            require_once(dirname(__FILE__) . '/Capirussa/Eobot/mock/MockEobotBrowser.php');
        } else if ($className === 'MockEobotClient') {
            require_once(dirname(__FILE__) . '/Capirussa/Eobot/mock/MockEobotClient.php');
        } else if (preg_match('/^Capirussa\\\\Eobot/', $className)) {
            $filePath = dirname(__FILE__) . '/../' . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
            if (file_exists($filePath)) {
                require_once($filePath);
            }
        }
        // @codeCoverageIgnoreEnd
    }
);
