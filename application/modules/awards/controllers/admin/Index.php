<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Controllers\Admin;

use Modules\Awards\Mappers\Awards as AwardsMapper;
use Modules\Awards\Models\Awards as AwardsModel;
use Modules\Awards\Mappers\Recipients as RecipientsMapper;
use Modules\Awards\Models\Recipient as RecipientModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Notifications as NotificationsMapper;
use Modules\User\Models\Notification as NotificationModel;
use Modules\Teams\Mappers\Teams as TeamsMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuAwards',
            $items
        );
    }

    public function indexAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        $userIds = [];
        $users = [];
        $teams = [];
        $teamIds = [];

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_entries') as $awardsId) {
                $awardsMapper->delete($awardsId);
            }
        }

        $awards = $awardsMapper->getAwards();
        foreach ($awards as $award) {
            foreach ($award->getRecipients() as $recipient) {
                if ($recipient->getTyp() == 1) {
                    $userIds[] = $recipient->getUtId();
                } else {
                    $teamIds[] = $recipient->getUtId();
                }
            }

            if (!empty($userIds)) {
                $users[$award->getId()] = $userMapper->getUserList(['id' => $userIds]);
            }

            if ($awardsMapper->existsTable('teams') && !empty($teamIds)) {
                $teamsMapper = new TeamsMapper();
                $teams[$award->getId()] = $teamsMapper->getTeams(['id' => $teamIds]);
            }
        }

        $this->getView()->set('awards', $awards)
            ->set('users', $users)
            ->set('teams', $teams);
    }

    public function treatAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        $awardsModel = new AwardsModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $awardsModel = $awardsMapper->getAwardsById($this->getRequest()->getParam('id'));

            if (!$awardsModel) {
                $this->redirect()
                    ->withMessage('awardNotFound', 'danger')
                    ->to(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('award', $awardsModel);

        if ($this->getRequest()->isPost()) {
            // Add BASE_URL if image starts with application to get a complete URL for validation
            $image = $this->getRequest()->getPost('image', null, true);
            if (!empty($image) && strncmp($image, 'application', 11) === 0) {
                $image = BASE_URL . '/' . $image;
            }

            Validation::setCustomFieldAliases([
                'utId' => 'invalidUserTeam',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'date'  => 'required|date:d.m.Y',
                'rank'  => 'required|numeric|integer|min:1',
                'image' => 'url',
                'utId'  => 'required',
                'event' => 'required',
                'page' => 'url'
            ]);

            if ($validation->isValid()) {
                if ($this->getRequest()->getParam('id')) {
                    $awardsModel->setId($this->getRequest()->getParam('id'));
                }
                $awardsModel->setDate(new \Ilch\Date($this->getRequest()->getPost('date', null, true)))
                    ->setRank($this->getRequest()->getPost('rank', 0, true))
                    ->setImage($this->getRequest()->getPost('image', '', true))
                    ->setEvent($this->getRequest()->getPost('event', '', true))
                    ->setURL($this->getRequest()->getPost('page', '', true));

                foreach ($this->getRequest()->getPost('utId') as $value) {
                    $recipientModel = new RecipientModel();
                    $recipientModel->setUtId(substr($value, 2))
                        ->setTyp(substr($value, 0, 1));

                    $awardsModel->addRecipient($recipientModel);
                }

                $id = $awardsMapper->save($awardsModel);
                $awardsModel->setId($id);

                // Notify the recipient of the award if this is enabled. Don't send a notification if this post is from editing an award.
                if ($this->getConfig()->get('awards_userNotification') && !$this->getRequest()->getParam('id')) {
                    $notificationsMapper = new NotificationsMapper();
                    $notificationModels = [];

                    foreach ($awardsModel->getRecipients() as $recipientModel) {
                        $users = [];
                        if ($recipientModel->getTyp() === 2) {
                            // The recipient is a team.
                            if ($awardsMapper->existsTable('teams')) {
                                $teamsMapper = new TeamsMapper();
                                $team = $teamsMapper->getTeamById($recipientModel->getUtId());
                                $users = $userMapper->getUserListByGroupId($team->getGroupId(), 1);
                            }
                        } else {
                            // The recipient is a user.
                            $notificationModel = new NotificationModel();
                            $notificationModel->setUserId($recipientModel->getUtId())
                                ->setModule('awards')
                                ->setMessage($this->getTranslator()->trans('awardReceived'))
                                ->setURL($this->getLayout()->getUrl(['module' => 'awards', 'controller' => 'index', 'action' => 'show', 'id' => $awardsModel], ''))
                                ->setType('awardReceived');
                            $notificationModels[] = $notificationModel;
                        }

                        // Notify all users of the team.
                        foreach ($users as $user) {
                            $notificationModel = new NotificationModel();
                            $notificationModel->setUserId($user->getId())
                                ->setModule('awards')
                                ->setMessage($this->getTranslator()->trans('awardReceivedAsTeam'))
                                ->setURL($this->getLayout()->getUrl(['module' => 'awards', 'controller' => 'index', 'action' => 'show', 'id' => $awardsModel], ''))
                                ->setType('awardReceivedAsTeam');
                            $notificationModels[] = $notificationModel;
                        }
                    }

                    $notificationsMapper->addNotifications($notificationModels);
                }

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
        }

        if ($awardsMapper->existsTable('teams')) {
            $teamsMapper = new TeamsMapper();
            $this->getView()->set('teams', $teamsMapper->getTeams());
        }

        $this->getView()->set('awardsMapper', $awardsMapper)
            ->set('users', $userMapper->getUserList(['confirmed' => 1]));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure() && !empty($this->getRequest()->getParam('id'))) {
            $awardsMapper = new AwardsMapper();
            $awardsMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
