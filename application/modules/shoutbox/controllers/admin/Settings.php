<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers\Admin;

use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'reset',
                'active' => false,
                'icon' => 'fa-solid fa-trash-can',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'reset'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuShoutbox',
            $items
        );
    }

    public function indexAction()
    {
        $userGroupMapper = new UserGroupMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShoutbox'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'limit'         => 'required|integer|min:1',
                'maxtextlength' => 'required|integer|min:20',
                'messagesPerPage' => 'required|integer|min:1',
                'messagesPerPageAdmincenter' => 'required|integer|min:1',
                'floodInterval' => 'required|integer|min:0',
            ]);

            if ($validation->isValid()) {
                if (empty($this->getRequest()->getPost('writeAccess'))) {
                    $writeAccess = '';
                } else {
                    $writeAccess = implode(',', $this->getRequest()->getPost('writeAccess'));
                }

                $this->getConfig()->set('shoutbox_limit', $this->getRequest()->getPost('limit'))
                    ->set('shoutbox_messagesPerPage', $this->getRequest()->getPost('messagesPerPage'))
                    ->set('shoutbox_messagesPerPageAdmincenter', $this->getRequest()->getPost('messagesPerPageAdmincenter'))
                    ->set('shoutbox_maxtextlength', $this->getRequest()->getPost('maxtextlength'))
                    ->set('shoutbox_floodInterval', $this->getRequest()->getPost('floodInterval'))
                    ->set('shoutbox_writeaccess', $writeAccess);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('limit', $this->getConfig()->get('shoutbox_limit'))
            ->set('messagesPerPage', $this->getConfig()->get('shoutbox_messagesPerPage'))
            ->set('messagesPerPageAdmincenter', $this->getConfig()->get('shoutbox_messagesPerPageAdmincenter'))
            ->set('maxtextlength', $this->getConfig()->get('shoutbox_maxtextlength'))
            ->set('floodInterval', $this->getConfig()->get('shoutbox_floodInterval'))
            ->set('userGroupList', $userGroupMapper->getGroupList())
            ->set('writeAccess', $this->getConfig()->get('shoutbox_writeaccess'));
    }
}
