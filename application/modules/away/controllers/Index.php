<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Away\Controllers;

use Modules\Admin\Mappers\Notifications as AdminNotificationsMapper;
use Modules\Admin\Models\Notification as AdminNotificationModel;
use Modules\Away\Mappers\Away as AwayMapper;
use Modules\Away\Mappers\Groups as AwayGroupMapper;
use Modules\Away\Models\Away as AwayModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Notifications as UserNotificationsMapper;
use Modules\User\Models\Notification as UserNotificationModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $awayMapper = new AwayMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuAway'), ['action' => 'index']);

        $currentlyEditingAway = null;
        $awayId = $this->getRequest()->getParam('id');
        if ($awayId && is_numeric($awayId)) {
            $currentlyEditingAway = $awayMapper->getAwayById($awayId);
        }

        $userCache = [];

        if ($this->getRequest()->getPost('saveAway')) {
            Validation::setCustomFieldAliases([
                'start' => 'when',
                'end' => 'when',
                'text' => 'description'
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'reason' => 'required',
                'start' => 'required|date:d.m.Y',
                'end' => 'required|date:d.m.Y',
                'text' => 'required',
                'calendarShow' => 'numeric|integer|min:1|max:1'
            ]);

            if ($validation->isValid()) {
                $post = [
                    'reason' => trim($this->getRequest()->getPost('reason')),
                    'start' => new \Ilch\Date(trim($this->getRequest()->getPost('start'))),
                    'end' => new \Ilch\Date(trim($this->getRequest()->getPost('end'))),
                    'text' => trim($this->getRequest()->getPost('text')),
                    'calendarShow' => trim($this->getRequest()->getPost('calendarShow'))
                ];

                $awayModel = new AwayModel();
                if ($currentlyEditingAway && $this->getUser() && ($currentlyEditingAway->getUserId() == $this->getUser()->getId())) {
                    // Entry found and the current user is the autor. Set id to update an existing entry. Reset status to reported.
                    $awayModel->setId($awayId);
                    $awayModel->setStatus(2);
                }
                $awayModel->setUserId($this->getUser()->getId())
                    ->setReason($post['reason'])
                    ->setStart($post['start'])
                    ->setEnd($post['end'])
                    ->setText($post['text'])
                    ->setShow((int)$post['calendarShow'] ?? 0);
                $awayMapper->save($awayModel);

                // Notify administrators and users if enabled.
                if ($this->getConfig()->get('away_adminNotification')) {
                    $notification = new AdminNotificationModel();
                    $adminNotificationsMapper = new AdminNotificationsMapper();

                    $notification->setModule('away');
                    $notification->setMessage($currentlyEditingAway ? $this->getLayout()->getTrans('awayUserUpdatedEntryMessage') : $this->getLayout()->getTrans('awayAdminNewEntryMessage'));
                    $notification->setURL($this->getLayout()->getUrl(['module' => 'away', 'controller' => 'index', 'action' => 'index'], 'admin'));
                    $notification->setType($currentlyEditingAway ? 'awayUserUpdatedEntry' : 'awayAdminNewEntry');
                    $adminNotificationsMapper->addNotification($notification);
                }

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
                        // Don't notify the user that just created the new entry.
                        if ($currentUserId === $user->getId()) {
                            continue;
                        }

                        $notification = new UserNotificationModel();
                        $notification->setUserId($user->getId());
                        $notification->setModule('away');
                        $notification->setMessage($currentlyEditingAway ? $this->getLayout()->getTrans('awayUserUpdatedEntryMessage') : $this->getLayout()->getTrans('awayNewEntryMessage'));
                        $notification->setURL($this->getLayout()->getUrl(['module' => 'away', 'controller' => 'index', 'action' => 'index']));
                        $notification->setType($currentlyEditingAway ? 'awayUserUpdatedEntry' : 'awayNewEntry');
                        $notifications[] = $notification;
                    }

                    $userNotificationsMapper->addNotifications($notifications);
                }

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'index'], $awayId ? ['id' => $awayId] : []));
        }

        $aways = $awayMapper->getAway();
        foreach ($aways as $away) {
            if (!\array_key_exists($away->getUserId(), $userCache)) {
                $userCache[$away->getUserId()] = $userMapper->getUserById($away->getUserId());
            }
        }

        if ($awayMapper->existsTable('calendar')) {
            $this->getView()->set('calendarShow', 1);
        }

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('userCache', $userCache);
        $this->getView()->set('aways', $aways);
        $this->getView()->set('currentlyEditingAway', $currentlyEditingAway);
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awayMapper = new AwayMapper();
            $awayMapper->update($this->getRequest()->getParam('id'));

            if ($this->getConfig()->get('away_userNotification')) {
                $userNotificationsMapper = new UserNotificationsMapper();
                $awayGroupMapper = new AwayGroupMapper();
                $userMapper = new UserMapper();

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
