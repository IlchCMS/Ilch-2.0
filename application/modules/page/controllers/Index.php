<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Page_IndexController extends Ilch_Controller_Frontend
{
	public function showAction()
	{
		$pageMapper = new Page_PageMapper();
		$id = $this->getRequest()->getParam('id');
		$locale = $this->getRequest()->getParam('locale');
		$page = $pageMapper->getPageByIdLocale($id, $locale);

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