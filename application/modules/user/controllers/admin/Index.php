<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\AuthToken as AuthTokenMapper;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Models\Group as GroupModel;
use Modules\User\Service\Password as PasswordService;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use \Ilch\Registry as Registry;
use Ilch\Validation;

/**
 * Handles action for the main admin configuration page.
 */
class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'menuActionNewUser',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuGroup',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuProfileFields',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'index'])
            ],
            [
                'name' => 'menuAuthProviders',
                'active' => false,
                'icon' => 'fa fa-key',
                'url'  => $this->getLayout()->getUrl(['controller' => 'providers', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuUser',
            $items
        );
    }

    /**
     * Shows a table with all users.
     */
    public function indexAction()
    {
        $pagination = new \Ilch\Pagination();
        $userMapper = new UserMapper();
        $authTokenMapper = new AuthTokenMapper();
        $statisticMapper = new StatisticMapper();
        
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuUser'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_users')) {
            foreach ($this->getRequest()->getPost('check_users') as $userId) {
                $deleteUser = $userMapper->getUserById($userId);

                if ($deleteUser->getId() != Registry::get('user')->getId()) {
                    if ($deleteUser->hasGroup(1) && $userMapper->getAdministratorCount() == 1) {} else {
                        $userMapper->delete($deleteUser->getId());
                        $authTokenMapper->deleteAllAuthTokenOfUser($deleteUser->getId());
                        $statisticMapper->deleteUserOnline($deleteUser->getId());
                    }
                }
            }
        }

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        if ($this->getRequest()->getParam('showsetfree')) {
            $entries = $userMapper->getUserList(['confirmed' => 0], $pagination);
        } else {
            $entries = $userMapper->getUserList(['confirmed' => 1], $pagination);
        }

        $this->getView()->set('userList', $entries)
            ->set('showDelUserMsg', $this->getRequest()->getParam('showDelUserMsg'))
            ->set('errorMsg', $this->getRequest()->getParam('errorMsg'))
            ->set('badge', count($userMapper->getUserList(['confirmed' => 0])))
            ->set('pagination', $pagination);
    }

    /**
     * Confirm user manually
     */
    public function setfreeAction()
    {
        if ($this->getRequest()->isSecure()) {
            $userMapper = new UserMapper();
            $emailsMapper = new EmailsMapper();
            $date = new \Ilch\Date();

            $model = new UserModel();
            $model->setId($this->getRequest()->getParam('id'));
            $model->setDateConfirmed($date->format("Y-m-d H:i:s", true));
            $model->setConfirmed(1);
            $userMapper->save($model);

            $user = $userMapper->getUserById($this->getRequest()->getParam('id'));
            $mailContent = $emailsMapper->getEmail('user', 'manually_confirm_mail', $user->getLocale());

            $layout = '';
            if (isset($_SESSION['layout'])) {
                $layout = $_SESSION['layout'];
            }

            if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/manuallyconfirm.php')) {
                $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/manuallyconfirm.php');
            } else {
                $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/user/layouts/mail/manuallyconfirm.php');
            }

            $messageReplace = [
                '{content}' => $mailContent->getText(),
                '{sitetitle}' => $this->getConfig()->get('page_title'),
                '{date}' => $date->format("l, d. F Y", true),
                '{name}' => $user->getName(),
                '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
            ];
            $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

            $mail = new \Ilch\Mail();
            $mail->setFromName($this->getConfig()->get('page_title'))
                ->setFromEmail($this->getConfig()->get('standardMail'))
                ->setToName($user->getName())
                ->setToEmail($user->getEmail())
                ->setSubject($this->getTranslator()->trans('automaticEmail'))
                ->setMessage($message)
                ->sent();

            $this->addMessage('freeSuccess');
        }

        $this->redirect(['action' => 'index', 'showsetfree' => 1]);
    }

    /**
     * Shows a form to create or edit a new user.
     */
    public function treatAction()
    {
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();
        $emailsMapper = new EmailsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuUser'), ['action' => 'index']);

        if (empty($this->getRequest()->getParam('id'))) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('addUser'), ['action' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('editUser'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $userData = $this->getRequest()->getPost();

            $rules = [
                'name' => 'required|unique:users,name',
                'email' => 'required|email|unique:users,email'
                ];

            if ($userData['id']) {
                $rules = [
                    'name' => 'required|unique:users,name,'.$userData['id'],
                    'email' => 'required|email|unique:users,email,'.$userData['id']
                    ];
            }

            Validation::setCustomFieldAliases([
                'name' => 'userName',
                'email' => 'userEmail'
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), $rules);

            if ($validation->isValid()) {
                $generated = false;
                if (!empty($userData['password'])) {
                    $userData['password'] = (new PasswordService())->hash($userData['password']);
                } else {
                    if (empty($userData['id'])) {
                        $pool = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";

                        $password = '';
                        for ($i = 0; $i < 10; $i++) {
                            $password .= $pool{rand(0, strlen($pool)-1)};
                        }
                        $userData['password'] = (new PasswordService())->hash($password);

                        $generated = true;
                    }
                }

                $user = $userMapper->loadFromArray($userData);

                if (empty($userData['groups'])) {
                    $userData['groups'][0] = 2;
                }
                foreach ($userData['groups'] as $groupId) {
                    $group = new GroupModel();
                    $group->setId($groupId);
                    $user->addGroup($group);
                }

                if (empty($userData['id'])) {
                    $date = new \Ilch\Date();
                    $user->setDateCreated($date);
                    $user->setLocale($this->getTranslator()->getLocale());
                }

                if ($generated AND empty($userData['id'])) {
                    $selector = bin2hex(openssl_random_pseudo_bytes(9));
                    $confirmedCode = bin2hex(openssl_random_pseudo_bytes(32));
                    $user->setSelector($selector);
                    $user->setConfirmedCode($confirmedCode);

                    $name = $user->getName();
                    $email = $user->getEmail();
                    $sitetitle = $this->getConfig()->get('page_title');
                    $confirmCode = '<a href="'.BASE_URL.'/index.php/user/login/newpassword/selector/'.$selector.'/code/'.$confirmedCode.'" class="btn btn-primary btn-sm">'.$this->getTranslator()->trans('confirmMailButtonText').'</a>';
                    $date = new \Ilch\Date();
                    $mailContent = $emailsMapper->getEmail('user', 'assign_password_mail', $user->getLocale());

                    $layout = '';
                    if (!empty($_SESSION['layout'])) {
                        $layout = $_SESSION['layout'];
                    }

                    if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/passwordchange.php')) {
                        $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/passwordchange.php');
                    } else {
                        $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/user/layouts/mail/passwordchange.php');
                    }
                    $messageReplace = [
                        '{content}' => $mailContent->getText(),
                        '{sitetitle}' => $sitetitle,
                        '{date}' => $date->format("l, d. F Y", true),
                        '{name}' => $name,
                        '{confirm}' => $confirmCode,
                        '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new \Ilch\Mail();
                    $mail->setFromName($this->getConfig()->get('page_title'))
                        ->setFromEmail($this->getConfig()->get('standardMail'))
                        ->setToName($name)
                        ->setToEmail($email)
                        ->setSubject($this->getTranslator()->trans('automaticEmail'))
                        ->setMessage($message)
                        ->sent();
                }

                $userId = $userMapper->save($user);

                // Check if user got locked and delete his authtokens.
                if (!empty($userData['locked']) && $userData['locked'] == 1) {
                    $authTokenMapper = new AuthTokenMapper();

                    $authTokenMapper->deleteAllAuthTokenOfUser($userId);
                }

                if (empty($userData['id'])) {
                    $this->addMessage('newUserMsg');
                } else {
                    $this->addMessage('success');
                }
                $this->redirect()
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $redirectTarget = ['action' => 'treat'];
            if (!empty($userData['id'])) {
                $redirectTarget = ['action' => 'treat', 'id' => $userData['id']];
            }

            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to($redirectTarget);
        }

        if (empty($userId)) {
            $userId = $this->getRequest()->getParam('id');
        }

        if ($userMapper->userWithIdExists($userId)) {
            $user = $userMapper->getUserById($userId);
        } else {
            $user = new UserModel();
            $group = new GroupModel();
            $group->setId(2);
            $user->addGroup($group);
        }

        $this->getView()->set('user', $user)
            ->set('groupList', $groupMapper->getGroupList());
    }

    /**
     * Deletes the given user.
     */
    public function deleteAction()
    {
        $userMapper = new UserMapper();
        $authTokenMapper = new AuthTokenMapper();
        $statisticMapper = new StatisticMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();

        $userId = $this->getRequest()->getParam('id');

        if ($userId && $this->getRequest()->isSecure()) {
            $deleteUser = $userMapper->getUserById($userId);

            /*
             * Admingroup has always id "1" because group is not deletable.
             */
            if ($deleteUser->getId() == Registry::get('user')->getId()) {
                $this->addMessage('delOwnUserProhibited', 'warning');
            } elseif ($deleteUser->hasGroup(1) && $userMapper->getAdministratorCount() === 1) {
                $this->addMessage('delLastAdminProhibited', 'warning');
                /*
                 * Delete adminuser only if he is not the last admin.
                 */
            } else {
                if ($deleteUser->getAvatar() != 'static/img/noavatar.jpg') {
                    unlink($deleteUser->getAvatar());
                }

                if (is_dir(APPLICATION_PATH.'/modules/user/static/upload/gallery/'.$userId)) {
                    $path = APPLICATION_PATH.'/modules/user/static/upload/gallery/'.$userId;
                    $files = array_diff(scandir($path), ['.', '..']);

                    foreach ($files as $file) {
                        unlink(realpath($path).'/'.$file);
                    }

                    rmdir($path);
                }

                $profileFieldsContentMapper->deleteProfileFieldContentByUserId($userId);
                if ($userMapper->delete($userId)) {
                    $authTokenMapper->deleteAllAuthTokenOfUser($userId);
                    $statisticMapper->deleteUserOnline($userId);
                    $this->addMessage('delUserMsg');
                }
            }
        }

        $this->redirect(['action' => 'index']);
    }
}
