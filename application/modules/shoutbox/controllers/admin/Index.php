<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Shoutbox\Controllers\Admin;

use Shoutbox\Mappers\Shoutbox as ShoutboxMapper;
use Shoutbox\Models\Shoutbox as ShoutboxModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Admin
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
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->url(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }



    public function indexAction()
    {
        $shoutboxMapper = new ShoutboxMapper();        
        $shoutbox = $shoutboxMapper->getShoutbox();
        
        $this->getView()->set('shoutbox', $shoutbox);
    }
    
    public function deleteAction()
    {
        $shoutboxMapper = new ShoutboxMapper();
        $shoutboxMapper->delete($this->getRequest()->getParam('id'));
        
        $this->addMessage('deleteSuccess');
        
        $this->redirect(array('action' => 'index'));
    }
}
