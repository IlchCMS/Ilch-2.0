<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Article\Controllers\Admin;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuArticle',
            array
            (
                array
                (
                    'name' => 'menuArticle',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuCats',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'cats', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'add',
                'icon' => 'fa fa-plus-circle',
                'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('settings'), array('action' => 'index'));

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('article_p_page', $this->getRequest()->getPost('articlePerPage'));
            $this->addMessage('saveSuccess');
        }
        

        $this->getView()->set('articlePerPage', $this->getConfig()->get('article_p_page'));
    }

}
