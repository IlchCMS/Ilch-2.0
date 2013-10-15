<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Page\Controllers\Admin;
use Page\Mappers\Page as PageMapper;
use Page\Models\Page as PageModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
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
		$pageMapper = new PageMapper();
		$pages = $pageMapper->getPageList();
		$this->getView()->set('pages', $pages);
	}

	public function deleteAction()
	{
		$pageMapper = new PageMapper();
		$pageMapper->delete($this->getRequest()->getParam('id'));
		$this->redirect(array('action' => 'index'));
	}

	public function changeAction()
	{
		$pageMapper = new PageMapper();

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
			$model = new PageModel();

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