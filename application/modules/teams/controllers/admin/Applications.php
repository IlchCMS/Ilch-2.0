<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Teams\Controllers\Admin;

use Modules\Teams\Mappers\Joins as JoinsMapper;
use Modules\Teams\Mappers\Teams as TeamsMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Service\Password as PasswordService;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;

class Applications extends \Ilch\Controller\Admin
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
                'name' => 'applications',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'applications', 'action' => 'index']),
                [
                    'name' => 'history',
                    'active' => false,
                    'icon' => 'fa fa-folder-open',
                    'url' => $this->getLayout()->getUrl(['controller' => 'applicationshistory', 'action' => 'index'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuTeams',
            $items
        );
    }

    public function indexAction()
    {
        $joinsMapper = new JoinsMapper();
        $teamsMapper = new TeamsMapper();
        $notificationsMapper = new NotificationsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuApplications'), ['action' => 'index']);

        $this->addMessage('rightsOfGroup', 'danger');

        $applications = $joinsMapper->getJoins();
        // Delete notifications for new applications if there are none anymore.
        if (empty($applications)) {
            $notificationsMapper->deleteNotificationsByType('teamsNewApplication');
        }

        $this->getView()->set('teamsMapper', $teamsMapper)
            ->set('joins', $applications);
    }

    public function showAction()
    {
        $joinsMapper = new JoinsMapper();
        $teamsMapper = new TeamsMapper();
        $userMapper = new UserMapper();

        $userDeleted = false;
        $join = $joinsMapper->getJoinById($this->getRequest()->getParam('id'));

        if ($join->getUserId() && !$userMapper->userWithIdExists($join->getUserId())) {
            $userDeleted = true;
        }

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuApplications'), ['controller' => 'applications', 'action' => 'index'])
            ->add($join->getName(), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        $this->addMessage('rightsOfGroup', 'danger');

        $this->getView()->set('joinsMapper', $joinsMapper)
            ->set('teamsMapper', $teamsMapper)
            ->set('join', $join)
            ->set('userDeleted', $userDeleted);
    }

    public function acceptAction()
    {
        if ($this->getRequest()->isSecure()) {
            $joinsMapper = new JoinsMapper();
            $teamsMapper = new TeamsMapper();
            $userMapper = new UserMapper();
            $groupMapper = new GroupMapper();
            $emailsMapper = new EmailsMapper();
            $passwordService = new PasswordService();

            $join = $joinsMapper->getJoinById($this->getRequest()->getParam('id'));
            $name = $join->getName();
            $email = $join->getEmail();
            $team = $teamsMapper->getTeamById($join->getTeamId());
            $newUser = false;

            if ($join->getUserId()) {
                $user = $userMapper->getUserById($join->getUserId());
                $mailContent = $emailsMapper->getEmail('teams', 'teams_accept_user_mail', $user->getLocale());

                $userMapper->addUserToGroup($join->getUserId(), $team->getGroupId());
            } else {
                $mailContent = $emailsMapper->getEmail('teams', 'teams_accept_mail', $this->getTranslator()->getLocale());
                $userGroup = $groupMapper->getGroupById($team->getGroupId());
                $selector = bin2hex(random_bytes(9));
                $confirmedCode = bin2hex(random_bytes(32));
                $password_string = '!@#$%*&abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ23456789';
                $password = substr(str_shuffle($password_string), 0, 12);

                $userModel = new UserModel();
                if ($join->getPlace()) {
                    $userModel->setCity($join->getPlace());
                }
                $userModel->setName($name)
                    ->setPassword($passwordService->hash($password))
                    ->setEmail($email)
                    ->setBirthday($join->getBirthday())
                    ->setGender($join->getGender())
                    ->setDateCreated($join->getDateCreated())
                    ->addGroup($userGroup)
                    ->setSelector($selector)
                    ->setConfirmedCode($confirmedCode)
                    ->setConfirmed(0);
                $userId = $userMapper->save($userModel);
                $join->setUserId($userId);
                $joinsMapper->save($join);
                $newUser = true;
            }

            $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
            $date = new \Ilch\Date();

            $layout = '';
            if (!empty($_SESSION['layout'])) {
                $layout = $_SESSION['layout'];
            }

            if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/teams/layouts/mail/accept.php')) {
                $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/teams/layouts/mail/accept.php');
            } else {
                $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/teams/layouts/mail/accept.php');
            }

            $messageReplace = [
                '{reply}' => $this->getTranslator()->trans('reply'),
                '{subject}' => $this->getTranslator()->trans('subjectAccept'),
                '{content}' => $this->getLayout()->purify($mailContent->getText()),
                '{sitetitle}' => $siteTitle,
                '{date}' => $date->format('l, d. F Y', true),
                '{name}' => $this->getLayout()->escape($name),
                '{teamname}' => $this->getLayout()->escape($team->getName()),
                '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
            ];

            if ($newUser) {
                $confirmCode = '<a href="'.BASE_URL.'/index.php/user/login/newpassword/selector/'.$selector.'/code/'.$confirmedCode.'" class="btn btn-primary btn-sm">'.$this->getTranslator()->trans('confirm').'</a>';
                $messageConfirm = ['{confirm}' => $confirmCode];
                $messageReplace = array_merge($messageReplace, $messageConfirm);
            }

            $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

            $mail = new \Ilch\Mail();
            $mail->setFromName($siteTitle)
                ->setFromEmail($this->getLayout()->escape($this->getConfig()->get('standardMail')))
                ->setToName($this->getLayout()->escape($name))
                ->setToEmail($this->getLayout()->escape($email))
                ->setSubject($this->getTranslator()->trans('subjectAccept'))
                ->setMessage($message)
                ->send();

            $joinsMapper->updateDecision($this->getRequest()->getParam('id'), 1);

            $this->redirect()
                ->withMessage('acceptSuccess')
                ->to(['action' => 'index']);
        }
    }

    public function rejectAction()
    {
        if ($this->getRequest()->isSecure()) {
            $joinsMapper = new JoinsMapper();
            $teamsMapper = new TeamsMapper();
            $emailsMapper = new EmailsMapper();
            $userMapper = new UserMapper();

            $join = $joinsMapper->getJoinById($this->getRequest()->getParam('id'));
            $team = $teamsMapper->getTeamById($join->getTeamId());
            $name = $this->getLayout()->escape($join->getName());
            $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
            $date = new \Ilch\Date();

            if ($join->getUserId()) {
                $user = $userMapper->getUserById($join->getUserId());
                $mailContent = $emailsMapper->getEmail('teams', 'teams_reject_mail', $user->getLocale());
            } else {
                $mailContent = $emailsMapper->getEmail('teams', 'teams_reject_mail', $this->getTranslator()->getLocale());
            }

            $layout = '';
            if (!empty($_SESSION['layout'])) {
                $layout = $_SESSION['layout'];
            }

            if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/teams/layouts/mail/reject.php')) {
                $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/teams/layouts/mail/reject.php');
            } else {
                $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/teams/layouts/mail/reject.php');
            }
            $messageReplace = [
                '{content}' => $this->getLayout()->purify($mailContent->getText()),
                '{sitetitle}' => $siteTitle,
                '{date}' => $date->format('l, d. F Y', true),
                '{name}' => $name,
                '{teamname}' => $this->getLayout()->escape($team->getName()),
                '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
            ];
            $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

            $mail = new \Ilch\Mail();
            $mail->setFromName($siteTitle)
                ->setFromEmail($this->getLayout()->escape($this->getConfig()->get('standardMail')))
                ->setToName($name)
                ->setToEmail($this->getLayout()->escape($join->getEmail()))
                ->setSubject($this->getTranslator()->trans('subjectReject'))
                ->setMessage($message)
                ->send();

            $joinsMapper->updateDecision($this->getRequest()->getParam('id'), 2);

            $this->redirect()
                ->withMessage('rejectSuccess')
                ->to(['action' => 'index']);
        }
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $joinsMapper = new JoinsMapper();

            $joinsMapper->delete($this->getRequest()->getParam('id'));
            
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}
