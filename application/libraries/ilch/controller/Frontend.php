<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Controller_Frontend extends Ilch_Controller_Base
{
	public function __construct(Ilch_Layout_Base $layout, Ilch_View $view, Ilch_Request $request, Ilch_Router $router, Ilch_Translator $translator)
	{
		parent::__construct($layout, $view, $request, $router, $translator);

		$this->getLayout()->setFile('default/index');
	}
}