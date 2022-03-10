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
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa fa-cogs',
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
                'limit'         => 'required|min:1',
                'maxwordlength' => 'required|min:10',
                'maxtextlength' => 'required|min:20'
            ]);

            if ($validation->isValid()) {
                if (empty($this->getRequest()->getPost('writeAccess'))) {
                    $writeAccess = '';
                } else {
                    $writeAccess = implode(",", $this->getRequest()->getPost('writeAccess'));
                }

                $this->getConfig()->set('shoutbox_limit', $this->getRequest()->getPost('limit'))
                    ->set('shoutbox_maxwordlength', $this->getRequest()->getPost('maxwordlength'))
                    ->set('shoutbox_maxtextlength', $this->getRequest()->getPost('maxtextlength'))
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
            ->set('maxwordlength', $this->getConfig()->get('shoutbox_maxwordlength'))
            ->set('maxtextlength', $this->getConfig()->get('shoutbox_maxtextlength'))
            ->set('userGroupList', $userGroupMapper->getGroupList())
            ->set('writeAccess', $this->getConfig()->get('shoutbox_writeaccess'));
    }
}
