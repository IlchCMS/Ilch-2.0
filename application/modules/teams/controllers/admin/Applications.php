<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Controllers\Admin;

use Modules\Teams\Mappers\Joins as JoinsMapper;
use Modules\Teams\Mappers\Teams as TeamsMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Service\Password as PasswordService;

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

        $this->getView()->set('teamsMapper', $teamsMapper);
        $this->getView()->set('joins', $joinsMapper->getJoins());
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

        $this->getView()->set('joinsMapper', $joinsMapper);
        $this->getView()->set('teamsMapper', $teamsMapper);
        $this->getView()->set('join', $join);
    }

    public function acceptAction()
    {
        if ($this->getRequest()->isSecure()) {
            $joinsMapper = new JoinsMapper();
            $teamsMapper = new TeamsMapper();
            $userMapper = new UserMapper();
            $passwordService = new PasswordService();
            $date = new \Ilch\Date();

            $join = $joinsMapper->getJoinById($this->getRequest()->getParam('id'));
            $name = $join->getName();
            $email = $join->getEmail();

            if ($join->getUserId()) {
                $mailContent = $this->getConfig()->get('teams_accept_user_mail');

                $userMapper->addUserToGroup($join->getUserId(), $join->getTeamId());
            } else {
                $groupMapper = new GroupMapper();

                $mailContent = $this->getConfig()->get('teams_accept_mail');
                $userGroup = $groupMapper->getGroupById($join->getTeamId());
                $selector = bin2hex(openssl_random_pseudo_bytes(9));
                $confirmedCode = bin2hex(openssl_random_pseudo_bytes(32));
                $password_string = '!@#$%*&abcdefghijklmnpqrstuwxyzABCDEFGHJKLMNPQRSTUWXYZ23456789';
                $password = substr(str_shuffle($password_string), 0, 12);

                $userModel = new UserModel();
                $userModel->setName($name)
                    ->setPassword($passwordService->hash($password))
                    ->setEmail($email)
                    ->setDateCreated($date->format("Y-m-d H:i:s", true))
                    ->addGroup($userGroup)
                    ->setSelector($selector)
                    ->setConfirmedCode($confirmedCode)
                    ->setConfirmed(0);
                $userMapper->save($userModel);
            }

            $team = $teamsMapper->getTeamById($join->getTeamId());
            $teamname = $team->getName();
            $sitetitle = $this->getConfig()->get('page_title');

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
                '{content}' => $mailContent,
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

            $join = $joinsMapper->getJoinById($this->getRequest()->getParam('id'));
            $team = $teamsMapper->getTeamById($join->getTeamId());
            $name = $join->getName();
            $email = $join->getEmail();
            $teamname = $team->getName();
            $sitetitle = $this->getConfig()->get('page_title');
            $date = new \Ilch\Date();

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
                '{content}' => $this->getConfig()->get('teams_reject_mail'),
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
