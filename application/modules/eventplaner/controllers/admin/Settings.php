<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Eventplaner\Controllers\Admin;

defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Controller\Admin 
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'eventplaner',
            array
            (
                array
                (
                    'name' => 'listView',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'newEvent',
                    'active' => true,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
                )
            )
        );
        
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'settings',
                'icon' => 'fa fa-cogs',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
            )
        );
    }
    
    public function indexAction() 
    {
        if ($this->getRequest()->isPost()) {
            //$this->getConfig()->set('gbook_autosetfree', $this->getRequest()->getPost('entrySettings'));
            //$this->addMessage('saveSuccess');
        }
        
        $this->getView()->set('config', $this->getConfig());
    }
}
