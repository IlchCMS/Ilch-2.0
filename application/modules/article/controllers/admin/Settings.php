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
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'add',
                'active' => false,
                'icon' => 'fa fa-plus-circle',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'cats' AND $this->getRequest()->getActionName() == 'index') {
            $items[1]['active'] = true;
        } elseif ($this->getRequest()->getControllerName() == 'index' AND $this->getRequest()->getActionName() == 'treat') {
            $items[2]['active'] = true;
        } elseif ($this->getRequest()->getControllerName() == 'settings' AND $this->getRequest()->getActionName() == 'index') {
            $items[3]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuArticle',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuArticle'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('article_articlesPerPage', $this->getRequest()->getPost('articlesPerPage'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('articlesPerPage', $this->getConfig()->get('article_articlesPerPage'));
    }
}
