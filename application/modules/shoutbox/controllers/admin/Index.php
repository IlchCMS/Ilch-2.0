<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers\Admin;

use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuShoutbox',
            $items
        );
    }

    public function indexAction()
    {
        $shoutboxMapper = new ShoutboxMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShoutbox'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_entries')) {
            foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                $shoutboxMapper->delete($entryId);
            }
        }

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('shoutbox', $shoutboxMapper->getShoutbox());
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $shoutboxMapper = new ShoutboxMapper();
            $shoutboxMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
