<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch Pluto
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

require_once APPLICATION_PATH.'/libraries/ilch/Functions.php';

/**
 * Loads all needed files for the given class.
 *
 * @param string $class
 * @throws InvalidArgumentException
 */
spl_autoload_register(function($class)
{
	$path = APPLICATION_PATH;
	$class = str_replace('_', '/' , $class);
	$classParts = explode('/', $class);

	if(strpos($class, 'Ilch/') !== false)
	{
		$class = end($classParts);
		$classPartsCount = count($classParts) - 1;
		unset($classParts[$classPartsCount]);
		$path = $path.'/libraries/'.strtolower(implode('/', $classParts));
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