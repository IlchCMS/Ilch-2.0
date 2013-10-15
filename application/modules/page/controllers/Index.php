<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Page;
defined('ACCESS') or die('no direct access');

class IndexController extends \Ilch\Controller\Frontend
{
	public function showAction()
	{
		$pageMapper = new PageMapper();
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