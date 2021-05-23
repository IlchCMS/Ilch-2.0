<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Teams\Controllers;

use Modules\Teams\Mappers\Teams as TeamsMapper;
use Modules\Teams\Mappers\Joins as JoinsMapper;
use Modules\Teams\Models\Joins as JoinsModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Mappers\ProfileFieldsTranslation as ProfileFieldsTranslationMapper;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Modules\Admin\Models\Notification as NotificationModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $teamsMapper = new TeamsMapper();
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $this->getLayout()->header()
            ->css('static/css/teams.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTeams'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['action' => 'index']);

        $profileIconFields = $profileFieldsMapper->getProfileFields(['type' => 2]);
        $profileFieldsTranslation = $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale());

        $this->getView()->set('userMapper', $userMapper)
            ->set('groupMapper', $groupMapper)
            ->set('profileFieldsContentMapper', $profileFieldsContentMapper)
            ->set('teams', $teamsMapper->getTeams())
            ->set('profileIconFields', $profileIconFields)
            ->set('profileFieldsTranslation', $profileFieldsTranslation);
    }

    public function teamAction()
    {
        $teamsMapper = new TeamsMapper();
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $this->getLayout()->header()
            ->css('static/css/teams.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTeams'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuTeam'), ['action' => 'team', 'id' => $this->getRequest()->getParam('id')]);

        $profileIconFields = $profileFieldsMapper->getProfileFields(['type' => 2]);
        $profileFieldsTranslation = $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale());

        $this->getView()->set('userMapper', $userMapper)
            ->set('groupMapper', $groupMapper)
            ->set('profileFieldsContentMapper', $profileFieldsContentMapper)
            ->set('team', $teamsMapper->getTeamById($this->getRequest()->getParam('id')))
            ->set('profileIconFields', $profileIconFields)
            ->set('profileFieldsTranslation', $profileFieldsTranslation);
    }

    public function joinAction()
    {
        $teamsMapper = new TeamsMapper();
        $joinsMapper = new JoinsMapper();
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();
        $captchaNeeded = captchaNeeded();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTeams'))
            ->add($this->getTranslator()->trans('menuJoin'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuJoin'), ['action' => 'join']);

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('bot') === '') {
            Validation::setCustomFieldAliases([
                'grecaptcha' => 'token',
            ]);

            $validationRules = [
                'name' => 'required|unique:teams_joins,name,0,undecided',
                'email' => 'required|email|unique:teams_joins,email,0,undecided',
                'teamId' => 'numeric|integer|min:1',
                'gender' => 'numeric|integer|min:1|max:3',
                'birthday' => 'required',
                'text' => 'required'
            ];

            if ($captchaNeeded) {
                if (in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                    $validationRules['token'] = 'required|grecaptcha:savejoin';
                } else {
                    $validationRules['captcha'] = 'required|captcha';
                }
            }

            if ($this->getUser()) {
                $validationRules['name'] = 'required|unique:teams_joins,name,0,undecided';
                $validationRules['email'] = 'required|email|unique:teams_joins,email,0,undecided';
            } else {
                $validationRules['name'] = 'required|unique:users,name|unique:teams_joins,name,0,undecided';
                $validationRules['email'] = 'required|email|unique:users,email|unique:teams_joins,email,0,undecided';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $model = new JoinsModel();
                $currentDate = new \Ilch\Date();

                if ($this->getUser()) {
                    $model->setUserId($this->getUser()->getId())
                        ->setGender($this->getUser()->getGender());
                } else {
                    $model->setGender($this->getRequest()->getPost('gender'));
                }
                $model->setName($this->getRequest()->getPost('name'))
                    ->setEmail($this->getRequest()->getPost('email'))
                    ->setPlace($this->getRequest()->getPost('place'))
                    ->setBirthday(new \Ilch\Date($this->getRequest()->getPost('birthday')))
                    ->setSkill($this->getRequest()->getPost('skill'))
                    ->setTeamId($this->getRequest()->getPost('teamId'))
                    ->setLocale($this->getTranslator()->getLocale())
                    ->setDateCreated($currentDate->toDb())
                    ->setText($this->getRequest()->getPost('text'))
                    ->setUndecided(1);
                $joinsMapper->save($model);

                // Add notification for the admincenter
                $notificationsMapper = new NotificationsMapper();
                $notificationModel = new NotificationModel();
                $notificationModel->setModule('teams');
                $notificationModel->setMessage($this->getTranslator()->trans('notificationMessage', $this->getRequest()->getPost('name')));
                $notificationModel->setURL($this->getLayout()->getUrl(['module' => 'admin/teams', 'controller' => 'applications', 'action' => 'index']));
                $notificationModel->setType('teamsNewApplication');

                $notificationsMapper->addNotification($notificationModel);

                // Send mail to team leaders if enabled
                if ($this->getRequest()->getParam('id')) {
                    $team = $teamsMapper->getTeamById($this->getRequest()->getParam('id'));
                    if (!empty($team) && $team->getNotifyLeader()) {
                        $emailsMapper = new EmailsMapper();
                        $leadersIds = explode(',', $team->getLeader());
                        $layout = '';
                        $subject = $this->getTranslator()->trans('subjectNewApplication');
                        $content = $this->getLayout()->purify($emailsMapper->getEmail('teams', 'teams_notifyLeader', $this->getTranslator()->getLocale())->getText());

                        if (isset($_SESSION['layout'])) {
                            $layout = $_SESSION['layout'];
                        }
                        if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/teams/layouts/mail/notifyLeader.php')) {
                            $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/teams/layouts/mail/notifyLeader.php');
                        } else {
                            $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/teams/layouts/mail/notifyLeader.php');
                        }

                        foreach($leadersIds as $leaderId) {
                            $receiver = $userMapper->getUserById($leaderId);
                            $date = new \Ilch\Date();
                            $messageReplace = [
                                '{subject}' => $subject,
                                '{content}' => $content,
                                '{name}' => $this->getLayout()->escape($receiver->getName()),
                                '{teamname}' => $this->getLayout()->escape($team->getName()),
                                '{sitetitle}' => $this->getLayout()->escape($this->getConfig()->get('page_title')),
                                '{date}' => $date->format('l, d. F Y', true),
                                '{footer}' => $this->getTranslator()->trans('noReplyMailFooter'),
                            ];
                            $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);
                            $mail = new \Ilch\Mail();
                            $mail->setFromName($this->getLayout()->escape($this->getConfig()->get('page_title')))
                                ->setFromEmail($this->getLayout()->escape($this->getConfig()->get('standardMail')))
                                ->setToName($this->getLayout()->escape($receiver->getName()))
                                ->setToEmail($this->getLayout()->escape($receiver->getEmail()))
                                ->setSubject($subject)
                                ->setMessage($message)
                                ->send();
                        }
                    }
                }

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag());

            if ($this->getRequest()->getParam('id')) {
                $this->redirect()
                    ->to(['action' => 'join', 'id' => $this->getRequest()->getParam('id')]);
            } else {
                $this->redirect()
                    ->to(['action' => 'join']);
            }
        }

        $this->getView()->set('teamsMapper', $teamsMapper)
            ->set('userMapper', $userMapper)
            ->set('groupMapper', $groupMapper)
            ->set('teams', $teamsMapper->getTeams());
        if ($captchaNeeded) {
            if (in_array((int)$this->getConfig()->get('captcha'), [2, 3])) {
                $googlecaptcha = new \Captcha\GoogleCaptcha($this->getConfig()->get('captcha_apikey'), null, (int)$this->getConfig()->get('captcha'));
                $this->getView()->set('googlecaptcha', $googlecaptcha);
            } else {
                $defaultcaptcha = new \Captcha\DefaultCaptcha();
                $this->getView()->set('defaultcaptcha', $defaultcaptcha);
            }
        }
        $this->getView()->set('captchaNeeded', $captchaNeeded);
    }
}
