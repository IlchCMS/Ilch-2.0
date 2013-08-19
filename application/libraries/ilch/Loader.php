<?php
/**
 * @author Dominik Meyer <kinimodmeyer@gmail.com>
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * @param string $class
 * @throws InvalidArgumentException
 */
function __autoload($class)
{
    $path = APPLICATION_PATH;
    
    if(strpos($class, 'Ilch_') !== FALSE)
    {
        $class = str_replace('_', '/' , $class);
        $path = $path.'/libraries';
    }
    else
    {
        $class = str_replace('_', '/' , $class);
        $classParts = explode('/', $class);
        $path = $path.'/modules/'.strtolower($classParts[0]);
        $lastCamel = preg_split('/(?<=\\w)(?=[A-Z])/', $class);
        $path = $path.'/'.strtolower(end($lastCamel).'s');
        $class = str_replace(end($lastCamel), '', $class);
        $class = str_replace($classParts[0].'/', '', $class);
    }

    if(file_exists($path.'/'. $class . '.php'))
    {
        require_once($path.'/'. $class . '.php');
    }
    else
    {
        throw new InvalidArgumentException('couldnt find file "'. $path.'/'. $class . '.php"');
    }
}