<?php
/**
 * @author Meyer Dominik
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
spl_autoload_register(function($class)
{
	$class = str_replace('\\', '/', $class);
	$classParts = explode('/', $class);
	$path = APPLICATION_PATH;

	if(strpos($classParts[0], 'Ilch') !== false)
	{
		$path = $path.'/libraries';
	}
	else
	{
		$camels = preg_split('/(?<=\\w)(?=[A-Z])/', $class);

		if(end($camels) === 'Plugin')
		{
			$path = $path.'/plugins/'.strtolower($classParts[0]);
		}
		elseif(end($camels) === 'Controller' && $classParts[0] == 'Admin')
		{
			$path = $path.'/modules/'.strtolower($classParts[0]).'/'.strtolower(end($camels).'s').'/admin';
		}
		else
		{
			$path = $path.'/modules/'.strtolower($classParts[0]).'/'.strtolower(end($camels).'s');
		}
		
		$class = str_replace(end($camels), '', $class);
		$class = str_replace($classParts[0].'/', '', $class);
	}

	if(file_exists($path.'/'. $class . '.php'))
	{
		require_once($path.'/'. $class . '.php');
	}
});