<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch Pluto
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Page_IndexController extends Ilch_Controller_Frontend
{
	public function indexAction()
	{
		
	}

	public function showAction()
	{
		$pageMapper = new Page_PageMapper();
		$pageKey = $this->getRequest()->getParam('page');
		$page = $pageMapper->getPageByKey($pageKey);

		if($page == null)
		{
			$this->getView()->set('content', 'page not found');
		}
		else
		{
			$this->getView()->set('content', $page->getContent());
		}
	}
}