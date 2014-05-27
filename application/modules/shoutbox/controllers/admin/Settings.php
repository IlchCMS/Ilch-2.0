<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers\Admin;

defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Controller\Admin 
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuShoutbox',
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
                    'name' => 'settings',
                    'active' => true,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }
    
    public function indexAction() 
    {
        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('shoutbox_limit', $this->getRequest()->getPost('limit'));
            $this->getConfig()->set('shoutbox_maxwordlength', $this->getRequest()->getPost('maxwordlength'));
            $this->addMessage('saveSuccess');
        }
        
        $this->getView()->set('limit', $this->getConfig()->get('shoutbox_limit'));
        $this->getView()->set('maxwordlength', $this->getConfig()->get('shoutbox_maxwordlength'));
    }
}
