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
    $is = '';

    if (strpos($classParts[0], 'Ilch') !== false) {
        $is = 'lib';
        $path = $path.'/libraries';
    } elseif (strpos($classParts[0], 'Boxes') !== false) {
        $is = 'box';
        $path = $path.'/';
    } else {
        $is = 'mod';
        $path = $path.'/modules';
    }

    /*
     * Transform the path to lower case.
     */
    if ($is == 'mod' || $is == 'box') {
        $lastClassPart = $classParts[count($classParts)-1];
        unset($classParts[count($classParts)-1]);
        $classParts = array_map('strtolower', $classParts);
        $class = implode('/', $classParts).'/'.$lastClassPart;
    }

    if (file_exists($path.'/'. $class . '.php')) {
        require_once($path.'/'. $class . '.php');
    }
});
