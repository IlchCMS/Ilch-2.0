<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Ilch_Controller_Admin extends Ilch_Controller
{
	public function __construct(Ilch_Layout $layout, Ilch_View $view, Ilch_Request $request, Ilch_Router $router, Ilch_Translator $translator)
	{
		parent::__construct($layout, $view, $request, $router, $translator);
		
		$menu = array();
		
		$this->getLayout()->set('menu', $menu);
		$this->getLayout()->setFile('admin/index');
	}
}