<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Linkus\Controllers\Admin;

defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Controller\Admin 
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuLinkus',
            array
            (
                array
                (
                    'name' => 'manage',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinkus'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('settings'), array('action' => 'index'));

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('linkus_html', $this->getRequest()->getPost('html'));
            $this->getConfig()->set('linkus_bbcode', $this->getRequest()->getPost('bbcode'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('linkus_html', $this->getConfig()->get('linkus_html'));
        $this->getView()->set('linkus_bbcode', $this->getConfig()->get('linkus_bbcode'));
    }
}
