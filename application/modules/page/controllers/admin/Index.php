<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch Pluto
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

class Page_Admin_IndexController extends Ilch_Controller_Admin
{
	public function init()
	{
		$this->getLayout()->addMenu
		(
			'menuSite',
			array
			(
				array
				(
					'name' => 'menuSites',
					'active' => true,
					'icon' => 'icon-th-list',
					'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
				),
			)
		);

		$this->getLayout()->addMenuAction
		(
			array
			(
				'name' => 'menuActionNewSite',
				'icon' => 'icon-plus-sign',
				'url'  => $this->getLayout()->url(array('controller' => 'index', 'action' => 'change'))
			)
		);
	}

	public function indexAction()
	{
		$pageMapper = new Page_PageMapper();
		$pages = $pageMapper->getPageList();

		$this->getView()->set('pages', $pages);

	}
	
	public function changeAction()
	{
		$pageMapper = new Page_PageMapper();

		if($this->getRequest()->getParam('id'))
		{
			if($this->getRequest()->getParam('locale') == '')
			{
				$locale = $this->getTranslator()->getLocale();
			}
			else
			{
				$locale = $this->getRequest()->getParam('locale');
			}

			$this->getView()->set('page', $pageMapper->getPageByIdLocale($this->getRequest()->getParam('id'), $locale));
		}

		$this->getView()->set('languages', $this->getTranslator()->getLocaleList());
		
		if($this->getRequest()->isPost())
		{
			$model = new Page_PageModel();

			if($this->getRequest()->getParam('id'))
			{
				$model->setId($this->getRequest()->getParam('id'));
			}

			$model->setTitle($this->getRequest()->getPost('pageTitle'));
			$model->setContent($this->getRequest()->getPost('pageContent'));
			$model->setLocale($this->getRequest()->getPost('pageLanguage'));
			$model->setPerma($this->getRequest()->getPost('pagePerma'));
			$pageMapper->save($model);
			
			$this->redirect(array('action' => 'index'));
		}
	}
}