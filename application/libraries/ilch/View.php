<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_View extends Ilch_Design_Base
{
	/**
	 * Loads a view script.
	 *
	 * @param string $viewScript
	 * @return string
	 */
	public function loadScript($viewScript)
	{
		ob_start();

		if(file_exists($viewScript))
		{
			include_once($viewScript);
		}

		return ob_get_clean();
	}
}