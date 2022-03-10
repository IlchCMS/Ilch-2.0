<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\AuthToken as AuthTokenMapper;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Mappers\ProfileFieldsTranslation as ProfileFieldsTranslationMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Models\Group as GroupModel;
use Modules\User\Service\Password as PasswordService;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\User\Mappers\AuthProvider;
use Modules\User\Mappers\Friends as FriendsMapper;
use Modules\User\Mappers\Dialog as DialogMapper;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Ilch\Registry;
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

        if ($this->getRequest()->getActionName() === 'treat' || $this->getRequest()->getActionName() === 'treatProfilefields') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
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
        $authProviderMapper = new AuthProvider();
        $friendsMapper = new FriendsMapper();
        $dialogMapper = new DialogMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuUser'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_users')) {
            foreach ($this->getRequest()->getPost('check_users') as $userId) {
                $deleteUser = $userMapper->getUserById($userId);

                if ($deleteUser->getId() != Registry::get('user')->getId() && (($deleteUser->isAdmin() && $this->getUser()->isAdmin()) || !$deleteUser->isAdmin())) {
                    if (!$deleteUser->hasGroup(1) || $userMapper->getAdministratorCount() > 1) {
                        $userMapper->delete($deleteUser->getId());
                        $authTokenMapper->deleteAllAuthTokenOfUser($deleteUser->getId());
                        $statisticMapper->deleteUserOnline($deleteUser->getId());
                        $authProviderMapper->deleteUser($userId);
                        $friendsMapper->deleteFriendsByUserId($userId);
                        $friendsMapper->deleteFriendByFriendUserId($userId);
                        $dialogMapper->deleteAllOfUser($userId);
                    }
                }
            }
        }

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        if ($this->getRequest()->getParam('showselectsdelete')) {
            $entries = $userMapper->getUserList(['selectsdelete >' => '1000-01-01 00:00:00'], $pagination);
        } elseif ($this->getRequest()->getParam('showsetfree')) {
            $entries = $userMapper->getUserList(['confirmed' => 0], $pagination);
            if (empty($entries)) {
                $notificationsMapper = new NotificationsMapper();
                $notificationsMapper->deleteNotificationsByType('userAwaitingApproval');
            }
        } elseif ($this->getRequest()->getParam('showlocked')) {
            $entries = $userMapper->getUserList(['locked' => 1], $pagination);
        } else {
            $entries = $userMapper->getUserList(['confirmed' => 1], $pagination);
        }

        $this->getView()->set('userList', $entries)
            ->set('showDelUserMsg', $this->getRequest()->getParam('showDelUserMsg'))
            ->set('errorMsg', $this->getRequest()->getParam('errorMsg'))
            ->set('badge', \count($userMapper->getUserList(['confirmed' => 0])))
            ->set('badgeLocked', \count($userMapper->getUserList(['locked' => 1])))
            ->set('badgeSelectsDelete', \count($userMapper->getUserList(['selectsdelete >' => '1000-01-01 00:00:00'])))
            ->set('timetodelete', $this->getConfig()->get('userdeletetime'))
            ->set('pagination', $pagination);
    }

    /**
     * selects Delete manually
     */
    public function selectsdeleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $userMapper = new UserMapper();
            $userMapper->selectsdelete($this->getRequest()->getParam('id'));
            $this->redirect(['action' => 'index', 'showselectsdelete' => 1]);
        }
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
            $model->setDateConfirmed($date->format('Y-m-d H:i:s', true));
            $model->setConfirmed(1);
            $userMapper->save($model);

            $user = $userMapper->getUserById($this->getRequest()->getParam('id'));
            $mailContent = $emailsMapper->getEmail('user', 'manually_confirm_mail', $user->getLocale());
            $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
            $username = $this->getLayout()->escape($user->getName());

            $layout = $_SESSION['layout'] ?? '';

            if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/manuallyconfirm.php')) {
                $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/user/layouts/mail/manuallyconfirm.php');
            } else {
                $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/user/layouts/mail/manuallyconfirm.php');
            }

            $messageReplace = [
                '{content}' => $this->getLayout()->purify($mailContent->getText()),
                '{sitetitle}' => $siteTitle,
                '{date}' => $date->format('l, d. F Y', true),
                '{name}' => $username,
                '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
            ];
            $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

            $mail = new \Ilch\Mail();
            $mail->setFromName($siteTitle)
                ->setFromEmail($this->getConfig()->get('standardMail'))
                ->setToName($username)
                ->setToEmail($user->getEmail())
                ->setSubject($this->getTranslator()->trans('automaticEmail'))
                ->setMessage($message)
                ->send();

            $this->addMessage('freeSuccess');
        }

        $this->redirect(['action' => 'index', 'showsetfree' => 1]);
    }

    /**
     * Unlock a locked user
     */
    public function unlockAction()
    {
        if ($this->getRequest()->isSecure()) {
            $userMapper = new UserMapper();

            $userModel = new UserModel();
            $userModel->setId($this->getRequest()->getParam('id'));
            $userModel->setLocked(0);
            $userMapper->save($userModel);

            $this->addMessage('unlockSuccess');
        }

        $this->redirect(['action' => 'index', 'showlocked' => 1]);
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
                $userbyid = $userMapper->getUserById($userData['id']);

                if ($userbyid->isAdmin() && !$this->getUser()->isAdmin()) {
                    $this->redirect(['action' => 'index']);
                }

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
                } elseif (empty($userData['id'])) {
                    $pool = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
                    $password = PasswordService::generateSecurePassword(10, $pool);
                    $userData['password'] = (new PasswordService())->hash($password);

                    $generated = true;
                }

                $user = $userMapper->loadFromArray($userData);

                if (empty($userData['groups'])) {
                    $userData['groups'][0] = 2;
                }
                foreach ($userData['groups'] as $groupId) {
                    if (($this->getUser()->isAdmin() && $groupId == 1) || $groupId != 1) {
                        $group = new GroupModel();
                        $group->setId($groupId);
                        $user->addGroup($group);
                    }
                }

                if (empty($userData['id'])) {
                    $date = new \Ilch\Date();
                    $user->setDateCreated($date);
                    $user->setLocale($this->getTranslator()->getLocale());
                }

                if ($generated && empty($userData['id'])) {
                    $selector = bin2hex(random_bytes(9));
                    $confirmedCode = bin2hex(random_bytes(32));
                    $user->setSelector($selector);
                    $user->setConfirmedCode($confirmedCode);

                    $name = $this->getLayout()->escape($user->getName());
                    $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
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
                        '{content}' => $this->getLayout()->purify($mailContent->getText()),
                        '{sitetitle}' => $siteTitle,
                        '{date}' => $date->format('l, d. F Y', true),
                        '{name}' => $name,
                        '{confirm}' => $confirmCode,
                        '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new \Ilch\Mail();
                    $mail->setFromName($siteTitle)
                        ->setFromEmail($this->getConfig()->get('standardMail'))
                        ->setToName($name)
                        ->setToEmail($user->getEmail())
                        ->setSubject($this->getTranslator()->trans('automaticEmail'))
                        ->setMessage($message)
                        ->send();
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

            if ($user->isAdmin() && !$this->getUser()->isAdmin()) {
                $this->redirect(['action' => 'index']);
            }
        } else {
            $user = new UserModel();
            $group = new GroupModel();
            $group->setId(2);
            $user->addGroup($group);
        }

        $this->getView()->set('user', $user)
            ->set('groupList', $groupMapper->getGroupList());
    }

    public function treatProfileAction()
    {
        $userMapper = new UserMapper();
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $user = $userMapper->getUserById($this->getRequest()->getParam('user'));

        if ($user) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuUser'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('editUser'), ['action' => 'treat'])
                ->add($this->getTranslator()->trans('editUserProfile'), ['action' => 'treatProfile', 'user' => $this->getRequest()->getParam('user')]);

            $profileFields = $profileFieldsMapper->getProfileFields(['type' => 2]);
            $profileFieldsContent = $profileFieldsContentMapper->getProfileFieldContentByUserId($this->getRequest()->getParam('user'));
            $profileFieldsTranslation = $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale());

            $this->getView()->set('user', $user);
            $this->getView()->set('profileFields', $profileFields);
            $this->getView()->set('profileFieldsContent', $profileFieldsContent);
            $this->getView()->set('profileFieldsTranslation', $profileFieldsTranslation);
        } else {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'User', 'errorText' => 'notFound']);
        }
    }

    public function deleteProfileFieldAction()
    {
        $profileFieldContentMapper = new ProfileFieldsContentMapper();
        $userId = $this->getRequest()->getParam('user');
        $profileFieldId = $this->getRequest()->getParam('id');
        $default = $this->getRequest()->getParam('default');

        if ($userId && $profileFieldId && $this->getRequest()->isSecure()) {
            $profileFieldContentMapper->deleteProfileFieldContentByUserAndFieldId($userId, $profileFieldId);

            $this->addMessage('success');
            $this->redirect(['action' => 'treatProfile', 'user' => $userId]);
        } elseif ($default) {
            $userMapper = new UserMapper();
            $user = $userMapper->getUserById($userId);

            if ($user) {
                switch ($default) {
                    case 'firstname':
                        $user->setFirstName('');
                        break;
                    case 'lastname':
                        $user->setLastName('');
                        break;
                    case 'city':
                        $user->setCity('');
                }
                $userMapper->save($user);

                $this->addMessage('success');
                $this->redirect(['action' => 'treatProfile', 'user' => $userId]);
            }
        }

        $this->redirect(['action' => 'index']);
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
        $authProviderMapper = new AuthProvider();
        $friendsMapper = new FriendsMapper();
        $dialogMapper = new DialogMapper();

        $userId = $this->getRequest()->getParam('id');

        if ($userId && $this->getRequest()->isSecure()) {
            $deleteUser = $userMapper->getUserById($userId);

            if ($deleteUser->isAdmin() && !$this->getUser()->isAdmin()) {
                $this->redirect(['action' => 'index']);
            }

            // Admingroup has always id "1" because group is not deletable.
            if ($deleteUser->getId() == Registry::get('user')->getId()) {
                $this->addMessage('delOwnUserProhibited', 'warning');
            } elseif ($deleteUser->hasGroup(1) && $userMapper->getAdministratorCount() === 1) {
                $this->addMessage('delLastAdminProhibited', 'warning');
            // Delete adminuser only if he is not the last admin.
            } else {
                if ($deleteUser->getAvatar() !== 'static/img/noavatar.jpg') {
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
                $authProviderMapper->deleteUser($userId);
                if ($userMapper->delete($userId)) {
                    $authTokenMapper->deleteAllAuthTokenOfUser($userId);
                    $statisticMapper->deleteUserOnline($userId);
                    $friendsMapper->deleteFriendsByUserId($userId);
                    $friendsMapper->deleteFriendByFriendUserId($userId);
                    $dialogMapper->deleteAllOfUser($userId);
                    $this->addMessage('delUserMsg');
                }
            }
        }

        $this->redirect(['action' => 'index']);
    }
}
