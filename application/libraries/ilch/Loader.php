<?php
/**
 * @author Dominik Meyer
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * Loads all needed files for the given class.
 *
 * @param string $class
 * @throws InvalidArgumentException
 */
spl_autoload_register(function($class)
{
    $path = APPLICATION_PATH;

    if(strpos($class, 'Ilch_') !== false)
    {
        $class = str_replace('_', '/' , $class);
        $path = $path.'/libraries';
    }
    else
    {
        $class = str_replace('_', '/' , $class);
        $classParts = explode('/', $class);
        $camels = preg_split('/(?<=\\w)(?=[A-Z])/', $class);

	if(end($camels) === 'Plugin')
	{
	    $path = $path.'/plugins/'.strtolower($classParts[0]);
	}
	else
	{
	    $path = $path.'/modules/'.strtolower($classParts[0]);
	    $path = $path.'/'.strtolower(end($camels).'s');
	}

        $class = str_replace(end($camels), '', $class);
        $class = str_replace($classParts[0].'/', '', $class);
    }

    if(file_exists($path.'/'. $class . '.php'))
    {
        require_once($path.'/'. $class . '.php');
    }
});