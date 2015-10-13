<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers\Admin;

use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;

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
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'settings',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
                )
            )
        );
    }

    public function indexAction()
    {
        $shoutboxMapper = new ShoutboxMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShoutbox'), array('action' => 'index'));

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_entries')) {
            foreach($this->getRequest()->getPost('check_entries') as $entryId) {
                $shoutboxMapper->delete($entryId);
            }
        }

        $this->getView()->set('shoutbox', $shoutboxMapper->getShoutbox());
    }

    public function deleteAction()
    {
        if($this->getRequest()->isSecure()) {
            $shoutboxMapper = new ShoutboxMapper();
            $shoutboxMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}
