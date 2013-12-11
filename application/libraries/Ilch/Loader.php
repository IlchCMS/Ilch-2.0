<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');
require_once APPLICATION_PATH.'/libraries/Ilch/Functions.php';

/**
 * Loads all needed files for the given class.
 *
 * @param string $class
 * @throws InvalidArgumentException
 */
spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $classParts = explode('/', $class);
    $path = APPLICATION_PATH;

    if (strpos($classParts[0], 'Ilch') !== false) {
        $path = $path.'/libraries';
    } else {
        $path = $path.'/modules';
        $lastClassPart = $classParts[count($classParts)-1];
        unset($classParts[count($classParts)-1]);
        $classParts = array_map('strtolower', $classParts);
        $class = implode('/', $classParts).'/'.$lastClassPart;
    }

    if (file_exists($path.'/'. $class . '.php')) {
        require_once($path.'/'. $class . '.php');
    }
});
