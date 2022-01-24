<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Away\Controllers\Admin;

use Ilch\Validation;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\Away\Mappers\Groups as AwayGroupsMapper;

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
            'menuAway',
            $items
        );
    }

    public function indexAction()
    {
        $groupMapper = new GroupMapper();
        $awayGroupsMapper = new AwayGroupsMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAway'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $rules = [
                'userNotification' => 'numeric|integer|min:0|max:1',
                'adminNotification' => 'numeric|integer|min:0|max:1',
            ];

            if ($this->getRequest()->getPost('userNotification')) {
                $rules['notifyGroups'] = 'required';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $rules);

            if ($validation->isValid()) {
                $this->getConfig()->set('away_userNotification', $this->getRequest()->getPost('userNotification'));
                $this->getConfig()->set('away_adminNotification', $this->getRequest()->getPost('adminNotification'));

                if ($this->getRequest()->getPost('notifyGroups')) {
                    $awayGroupsMapper->save($this->getRequest()->getPost('notifyGroups'));
                }

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

        $groups = $awayGroupsMapper->getGroups();

        if ($groups === null) {
            $groups = [1,2];
        }

        $userGroupList = $groupMapper->getGroupList();

        // Remove the user group 'guest'.
        foreach ($userGroupList as $index => $userGroup) {
            if ($userGroup->getId() === 3) {
                array_splice($userGroupList, $index, 1);
                break;
            }
        }

        $this->getView()->set('userNotification', $this->getConfig()->get('away_userNotification'))
            ->set('notifyGroups', $this->getConfig()->get('away_notifyGroups'))
            ->set('adminNotification', $this->getConfig()->get('away_adminNotification'))
            ->set('groups', $groups)
            ->set('userGroupList', $userGroupList);
    }
}
