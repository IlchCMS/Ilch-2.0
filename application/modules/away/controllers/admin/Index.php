<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Away\Controllers\Admin;

use Modules\Away\Mappers\Away as AwayMapper;
use Modules\Away\Mappers\Groups as AwayGroupMapper;
use Modules\User\Mappers\Notifications as UserNotificationsMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\Notification as UserNotificationModel;

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
            'menuAway',
            $items
        );
    }

    public function indexAction()
    {
        $awayMapper = new AwayMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAway'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_aways') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_aways') as $id) {
                $awayMapper->delete($id);
            }
        }

        $userCache = [];
        $aways = $awayMapper->getAway();

        foreach ($aways as $away) {
            if (!\array_key_exists($away->getUserId(), $userCache)) {
                $userCache[$away->getUserId()] = $userMapper->getUserById($away->getUserId());
            }
        }

        $this->getView()->set('userCache', $userCache);
        $this->getView()->set('aways', $aways);
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awayMapper = new AwayMapper();
            $awayMapper->update($this->getRequest()->getParam('id'));

            if ($this->getConfig()->get('away_userNotification')) {
                $userNotificationsMapper = new UserNotificationsMapper();
                $awayGroupMapper = new AwayGroupMapper();

                $notifications = [];
                $userGroups = $awayGroupMapper->getGroups();
                $users = $userMapper->getUserListByGroupId($userGroups, 1);
                // Users might be in several groups. Remove duplicates so each user only gets one notification.
                $users = array_unique($users, SORT_REGULAR);
                $currentUserId = $this->getUser()->getId();

                foreach ($users as $user) {
                    // Don't notify the user that just changed the entry.
                    if ($currentUserId === $user->getId()) {
                        continue;
                    }

                    $notification = new UserNotificationModel();
                    $notification->setUserId($user->getId());
                    $notification->setModule('away');
                    $notification->setMessage($this->getLayout()->getTrans('awayChangedEntryMessage'));
                    $notification->setURL($this->getLayout()->getUrl(['module' => 'away', 'controller' => 'index', 'action' => 'index']));
                    $notification->setType('awayChangedEntry');
                    $notifications[] = $notification;
                }

                $userNotificationsMapper->addNotifications($notifications);
            }

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awayMapper = new AwayMapper();
            $awayMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
