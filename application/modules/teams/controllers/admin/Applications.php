<?php
/**
 * @copyright Ilch 2.0
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
                'url' => $this->getLayout()->getUrl(['controller' => 'applications', 'action' => 'index'])
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
            'menuTeams',
            $items
        );
    }

    public function indexAction()
    {
        $joinsMapper = new JoinsMapper();
        $teamsMapper = new TeamsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuApplications'), ['action' => 'index']);

        $this->addMessage('rightsOfGroup', 'danger');

        $this->getView()->set('teamsMapper', $teamsMapper)
            ->set('joins', $joinsMapper->getJoins());
    }

    public function showAction()
    {
        $joinsMapper = new JoinsMapper();
        $teamsMapper = new TeamsMapper();

        $join = $joinsMapper->getJoinById($this->getRequest()->getParam('id'));

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuApplications'), ['controller' => 'applications', 'action' => 'index'])
            ->add($join->getName(), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        $this->addMessage('rightsOfGroup', 'danger');

        $this->getView()->set('joinsMapper', $joinsMapper)
            ->set('teamsMapper', $teamsMapper)
            ->set('join', $join);
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

            if ($join->getUserId()) {
                $user = $userMapper->getUserById($join->getUserId());
                $mailContent = $emailsMapper->getEmail('teams', 'teams_accept_user_mail', $user->getLocale());

                $userMapper->addUserToGroup($join->getUserId(), $join->getTeamId());
            } else {
                $mailContent = $emailsMapper->getEmail('teams', 'teams_accept_mail', $this->getTranslator()->getLocale());
                $userGroup = $groupMapper->getGroupById($join->getTeamId());
                $selector = bin2hex(openssl_random_pseudo_bytes(9));
                $confirmedCode = bin2hex(openssl_random_pseudo_bytes(32));
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
                $userMapper->save($userModel);
            }

            $team = $teamsMapper->getTeamByGroupId($join->getTeamId());
            $teamname = $team->getName();
            $sitetitle = $this->getConfig()->get('page_title');
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
                '{content}' => $mailContent->getText(),
                '{sitetitle}' => $sitetitle,
                '{date}' => $date->format("l, d. F Y", true),
                '{name}' => $name,
                '{teamname}' => $teamname,
                '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
            ];

            if (!$join->getUserId()) {
                $confirmCode = '<a href="'.BASE_URL.'/index.php/user/login/newpassword/selector/'.$selector.'/code/'.$confirmedCode.'" class="btn btn-primary btn-sm">'.$this->getTranslator()->trans('confirm').'</a>';
                $messageConfirm = ['{confirm}' => $confirmCode];
                $messageReplace = array_merge($messageReplace, $messageConfirm);
            }

            $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

            $mail = new \Ilch\Mail();
            $mail->setTo($email,$name)
                ->setSubject($this->getTranslator()->trans('subjectAccept'))
                ->setFrom($this->getConfig()->get('standardMail'), $sitetitle)
                ->setMessage($message)
                ->addGeneralHeader('Content-Type', 'text/html; charset="utf-8"');
            $mail->setAdditionalParameters('-t '.'-f'.$this->getConfig()->get('standardMail'));
            $mail->send();

            $joinsMapper->delete($this->getRequest()->getParam('id'));

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
            $team = $teamsMapper->getTeamByGroupId($join->getTeamId());
            $name = $join->getName();
            $email = $join->getEmail();
            $teamname = $team->getName();
            $sitetitle = $this->getConfig()->get('page_title');
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
                '{content}' => $mailContent->getText(),
                '{sitetitle}' => $sitetitle,
                '{date}' => $date->format("l, d. F Y", true),
                '{name}' => $name,
                '{teamname}' => $teamname,
                '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
            ];
            $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

            $mail = new \Ilch\Mail();
            $mail->setTo($email,$name)
                ->setSubject($this->getTranslator()->trans('subjectReject'))
                ->setFrom($this->getConfig()->get('standardMail'), $sitetitle)
                ->setMessage($message)
                ->addGeneralHeader('Content-Type', 'text/html; charset="utf-8"');
            $mail->setAdditionalParameters('-t '.'-f'.$this->getConfig()->get('standardMail'));
            $mail->send();

            $joinsMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('rejectSuccess')
                ->to(['action' => 'index']);
        }
    }
}
