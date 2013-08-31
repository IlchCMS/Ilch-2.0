<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_View extends Ilch_Design_Abstract
{
	public function load($module, $controller, $action = '')
	{
		ob_start();

		if(empty($action))
		{
			$view = APPLICATION_PATH.'/modules/'.$module.'/views/'.$controller.'.php';
		}
		else
		{
			$view = APPLICATION_PATH.'/modules/'.$module.'/views/'.$controller.'/'.$action.'.php';

			if(file_exists(APPLICATION_PATH.'/modules/'.$module.'/views/'.$controller.'Helper.php'))
			{
				include_once(APPLICATION_PATH.'/modules/'.$module.'/views/'.$controller.'Helper.php');
				$this->helper = new Helper($this);
			}
		}


		if(file_exists($view))
		{
			include_once($view);
		}

		return ob_get_clean();
	}
}