<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Controllers\Admin;

use Guestbook\Mappers\Settings as SettingsMapper;

defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Controller\Admin 
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'Guestbook',
            array
            (
                array
                (
                    'name' => 'Verwalten',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'Settings',
                'icon' => 'fa fa-cogs',
                'url'  => $this->getLayout()->url(array('controller' => 'settings', 'action' => 'index'))
            )
        );
    }
    
    public function indexAction() 
    {
        $SettingsMapper = new SettingsMapper();
        
        if ($this->getRequest()->isPost()) {
            $entrySettings = array
                (
                    'entrySettings' =>$this->getRequest()->getPost('entrySettings'),
                );
            $SettingsMapper->saveSettings($entrySettings);
            $this->addMessage('successful');
        }
        $this->getView()->set('entrySettings', $SettingsMapper->getallSettings('entrySettings'));
    }
}


