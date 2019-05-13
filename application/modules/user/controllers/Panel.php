<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;
use Modules\User\Mappers\Dialog as DialogMapper;
use Modules\User\Models\Dialog as DialogModel;
use Modules\User\Mappers\Setting as SettingMapper;
use Modules\User\Controllers\Base as BaseController;
use Modules\User\Service\Password as PasswordService;
use Modules\User\Mappers\Gallery as GalleryMapper;
use Modules\User\Models\GalleryItem as GalleryItemModel;
use Modules\User\Mappers\GalleryImage as GalleryImageMapper;
use Modules\User\Models\GalleryImage as GalleryImageModel;
use Modules\User\Mappers\Media as MediaMapper;
use Modules\User\Mappers\Friends as FriendsMapper;
use Ilch\Date as IlchDate;
use Ilch\Validation;

use Modules\User\Mappers\AuthToken as AuthTokenMapper;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Models\ProfileFieldContent as ProfileFieldContentModel;
use Modules\User\Mappers\ProfileFieldsTranslation as ProfileFieldsTranslationMapper;
use Modules\User\Mappers\AuthProvider as AuthProvider;

class Panel extends BaseController
{
    public function indexAction()
    {
        $friendsMapper = new FriendsMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index', 'user' => $this->getUser()->getId()]);

        $this->getView()->set('openFriendRequests', $friendsMapper->getOpenFriendRequests($this->getUser()->getId()));
    }

    public function settingsAction()
    {
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'panel', 'action' => 'settings']);
    }

    public function profileAction()
    {
        $profilMapper = new UserMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'panel', 'action' => 'settings'])
            ->add($this->getTranslator()->trans('menuEditProfile'), ['controller' => 'panel', 'action' => 'profile']);

        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $profileFieldsContent = $profileFieldsContentMapper->getProfileFieldContentByUserId($this->getUser()->getId());
        $profileFields = $profileFieldsMapper->getProfileFields();
        $profileFieldsTranslation = $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale());

        $this->getView()->set('profileFieldsContent', $profileFieldsContent)
            ->set('profileFields', $profileFields)
            ->set('profileFieldsTranslation', $profileFieldsTranslation);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'email' => 'profileEmail',
                'homepage' => 'profileHomepage'
            ]);

            $post = [
                'email' => trim($this->getRequest()->getPost('email')),
                'firstname' => trim($this->getRequest()->getPost('first-name')),
                'lastname' => trim($this->getRequest()->getPost('last-name')),
                'gender' => trim($this->getRequest()->getPost('gender')),
                'city' => trim($this->getRequest()->getPost('city'))
            ];

            foreach ($profileFields as $profileField) {
                $index = 'profileField'.$profileField->getId();
                $post[$index] = trim($this->getRequest()->getPost($index));
            }

            $validation = Validation::create($post, [
                'email' => 'required|email'
            ]);

            $birthday = '';
            if ($this->getRequest()->getPost('birthday') != '') {
                $birthday = new \Ilch\Date($this->getRequest()->getPost('birthday'));
            }

            if ($validation->isValid()) {
                $model = new UserModel();
                $model->setId($this->getUser()->getId())
                    ->setEmail($post['email'])
                    ->setFirstName($post['firstname'])
                    ->setLastName($post['lastname'])
                    ->setGender($post['gender'])
                    ->setCity($post['city'])
                    ->setBirthday($birthday);
                $profilMapper->save($model);

                foreach ($profileFields as $profileField) {
                    $index = 'profileField'.$profileField->getId();
                    $profileFieldsContent = new ProfileFieldContentModel();
                    $profileFieldsContent->setFieldId($profileField->getId())
                        ->setUserId($this->getUser()->getId())
                        ->setValue($post[$index]);
                    $profileFieldsContentMapper->save($profileFieldsContent);
                }

                $this->redirect(['action' => 'profile']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput($post)
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'profile']);
            }
        }
    }

    public function avatarAction()
    {
        $profilMapper = new UserMapper();
        $settingMapper = new SettingMapper();

        $avatarAllowedFiletypes = $this->getConfig()->get('avatar_filetypes');
        $avatarHeight = $this->getConfig()->get('avatar_height');
        $avatarWidth = $this->getConfig()->get('avatar_width');
        $avatarSize = $this->getConfig()->get('avatar_size');

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'panel', 'action' => 'settings'])
            ->add($this->getTranslator()->trans('menuAvatar'), ['controller' => 'panel', 'action' => 'avatar']);

        if ($this->getRequest()->isPost() && !empty($_FILES['avatar']['name'])) {
            $path = $this->getConfig()->get('avatar_uploadpath');
            $file = $_FILES['avatar']['name'];
            $file_tmpe = $_FILES['avatar']['tmp_name'];
            $endung = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $file_size = $_FILES['avatar']['size'];
            $imageInfo = getimagesize($file_tmpe);

            if (in_array($endung, explode(' ', $avatarAllowedFiletypes)) && strpos($imageInfo['mime'], 'image/') === 0) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];

                if ($file_size <= $avatarSize) {
                    $avatar = $path.$this->getUser()->getId().'.'.$endung;

                    if ($this->getUser()->getAvatar() != '') {
                        $settingMapper = new SettingMapper();
                        $settingMapper->delAvatarById($this->getUser()->getId());
                    }

                    if (move_uploaded_file($file_tmpe, $avatar)) {
                        if ($width > $avatarWidth OR $height > $avatarHeight) {
                            $upload = new \Ilch\Upload();

                            if (!$upload->enoughFreeMemory($avatar)) {
                                unlink($avatar);
                                $this->addMessage('failedFilesize', 'warning');
                            } else {
                                $thumb = new \Thumb\Thumbnail();
                                $thumb -> Thumbsize = ($avatarWidth <= $avatarHeight) ? $avatarWidth : $avatarHeight;
                                $thumb -> Square = true;
                                $thumb -> Thumblocation = $path;
                                $thumb -> Cropimage = [3,1,50,50,50,50];
                                $thumb -> Createthumb($avatar, 'file');
                                $this->addMessage('successAvatar');
                            }
                        }
                    }

                    $model = new UserModel();
                    $model->setId($this->getUser()->getId())
                        ->setAvatar($avatar);
                    $profilMapper->save($model);
                } else {
                    $this->addMessage('failedFilesize', 'warning');
                }
            } else {
                $this->addMessage('failedFiletypes', 'warning');
            }

            $this->redirect(['action' => 'avatar']);
        } elseif ($this->getRequest()->isPost() && $this->getRequest()->getPost('avatar_delete') != '') {
            $settingMapper = new SettingMapper();
            $settingMapper->delAvatarById($this->getUser()->getId());

            $this->addMessage('avatarSuccessDelete');
            $this->redirect(['action' => 'avatar']);
        }

        $this->getView()->set('settingMapper', $settingMapper)
            ->set('avatar_height', $avatarHeight)
            ->set('avatar_width', $avatarWidth)
            ->set('avatar_size', $avatarSize)
            ->set('avatar_filetypes', $avatarAllowedFiletypes);
    }

    public function signatureAction()
    {
        $profilMapper = new UserMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'panel', 'action' => 'settings'])
            ->add($this->getTranslator()->trans('menuSignature'), ['controller' => 'panel', 'action' => 'signature']);

        if ($this->getRequest()->isPost()) {
            $model = new UserModel();
            $model->setId($this->getUser()->getId())
                ->setSignature(trim($this->getRequest()->getPost('signature')));
            $profilMapper->save($model);

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'signature']);
        }
    }

    public function passwordAction()
    {
        $profilMapper = new UserMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'panel', 'action' => 'settings'])
            ->add($this->getTranslator()->trans('menuPassword'), ['controller' => 'panel', 'action' => 'password']);

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'password' => 'profileNewPassword',
                'password2' => 'profileNewPasswordRetype',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'password' => 'required|min:6,string|max:30,string',
                'password2' => 'required|same:password|min:6,string|max:30,string'
            ]);

            if ($validation->isValid()) {
                // Delete all stored authTokens of a user when he changes his password.
                // This will invalidate all possibly stolen rememberMe-cookies.
                $authTokenMapper = new \Modules\User\Mappers\AuthToken();
                $authTokenMapper->deleteAllAuthTokenOfUser($this->getUser()->getId());

                $model = new UserModel();
                $model->setId($this->getUser()->getId())
                    ->setPassword((new PasswordService())->hash($this->getRequest()->getPost('password')));
                $profilMapper->save($model);

                $this->redirect()
                    ->withMessage('passwordSuccess')
                    ->to(['action' => 'password']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'password']);
            }
        }
    }

    public function settingAction()
    {
        $profilMapper = new UserMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'panel', 'action' => 'settings'])
            ->add($this->getTranslator()->trans('menuSetting'), ['controller' => 'panel', 'action' => 'setting']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'optMail' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $model = new UserModel();
                $model->setId($this->getUser()->getId())
                    ->setLocale($this->getRequest()->getPost('locale'))
                    ->setOptMail($this->getRequest()->getPost('optMail'));
                $profilMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'setting']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'setting']);
            }
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
    }
    public function deleteaccountAction()
    {
        $userMapper = new UserMapper();
        $authTokenMapper = new AuthTokenMapper();
        $statisticMapper = new StatisticMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $authProviderMapper = new AuthProvider();
        $friendsMapper = new FriendsMapper();
        $dialogMapper = new DialogMapper();
        
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'panel', 'action' => 'settings'])
            ->add($this->getTranslator()->trans('menuDeleteAccount'), ['controller' => 'panel', 'action' => 'deleteaccount']);

        if ($this->getRequest()->isSecure()) {
            $userId = $this->getUser()->getId();
            if ($this->getUser()->hasGroup(1) && $userMapper->getAdministratorCount() === 1) {
                $this->addMessage('delLastAdminProhibited', 'warning');
                $this->redirect(['controller' => 'panel', 'action' => 'deleteaccount']);
            }else{
                if ($this->getConfig()->get('userdeletetime') == 0){
                    if ($this->getUser()->getAvatar() != 'static/img/noavatar.jpg') {
                        unlink($this->getUser()->getAvatar());
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
                        $dialogMapper->unhideAllDialogsByUser($userId);
                    }

                    if (!empty($_COOKIE['remember'])) {
                        list($selector) = explode(':', $_COOKIE['remember']);
                        $authTokenMapper->deleteAuthToken($selector);
                        setcookie('remember', '', time() - 3600, '/', $_SERVER['SERVER_NAME'], (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off'), true);
                    }

                    $_SESSION = [];
                    \Ilch\Registry::remove('user');

                    if (ini_get("session.use_cookies")) {
                        $params = session_get_cookie_params();
                        setcookie(session_name(), '', time() - 42000, $params["path"],
                            $params["domain"], $params["secure"], $params["httponly"]
                        );
                    }

                    session_destroy();
                    
                    $this->redirect([]);
                }else{
                    $userMapper->selectsdelete($userId, new \Ilch\Date());
                    $this->redirect(['module' => 'admin/admin', 'controller' => 'login', 'action' => 'logout', 'from_frontend' => 1]);
                }
            }
        }
        
        $this->getView()->set('delete_time', $this->getConfig()->get('userdeletetime'));
    }

    public function dialogAction()
    {
        $dialogMapper = new DialogMapper();
        $ilchdate = new IlchDate;

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuDialog'), ['controller' => 'panel', 'action' => 'dialog']);

        $c_id = $this->getRequest()->getParam('id');

        if ($c_id) {
            $user = $dialogMapper->getDialogCheckByCId($c_id);

            if ($this->getUser()->getId() != $user->getUserTwo()) {
                $user_one = $user->getUserTwo();
                $user_two = $user->getUserOne();
            } else {
                $user_one = $user->getUserOne();
                $user_two = $user->getUserTwo();
            }

            if ($this->getUser()->getId() == $user_two) {
                if ($this->getRequest()->isPost()) {

                    $u_id_fk = $this->getUser()->getId();
                    $text = trim($this->getRequest()->getPost('text'));

                    $model = new DialogModel();
                    $model->setCId($c_id)
                        ->setId($u_id_fk)
                        ->setTime($ilchdate->toDb())
                        ->setText($text);
                    $dialogMapper->save($model);

                    $this->redirect(['action' => 'dialog','id'=> $c_id]);
                }

                $this->getView()->set('inbox', $dialogMapper->getDialogMessage($c_id));

                $dialog = $dialogMapper->getReadLastOneDialog($c_id);
                if ($dialog AND $dialog->getUserOne() != $this->getUser()->getId()) {
                    $model = new DialogModel();
                    $model->setCrId($dialog->getCrId())
                        ->setRead(1);
                    $dialogMapper->updateRead($model);
                }
            } else {
                $this->redirect(['action' => 'dialog']);
            }

            $dialogMapper->unhideDialog($c_id, $this->getUser()->getId());
            $this->getView()->set('dialog', $dialogMapper->getDialogByCId($user_one));
        }

        $this->getView()->set('dialogs', $dialogMapper->getDialog($this->getUser()->getId(), ($this->getRequest()->getParam('showhidden') == 1)));
        $this->getView()->set('dialogsHidden', $dialogMapper->hasHiddenDialog($this->getUser()->getId()));
    }

    public function hidedialogAction()
    {
        if ($this->getRequest()->isSecure()) {
            $c_id = $this->getRequest()->getParam('id');

            $dialogMapper = new DialogMapper();
            $dialog = $dialogMapper->getDialogCheckByCId($c_id);

            // Allow hiding of dialog if user is part of the conversation.
            if (($dialog->getUserOne() == $this->getUser()->getId()) || ($dialog->getUserTwo() == $this->getUser()->getId())) {
                $dialogMapper->hideDialog($c_id, $this->getUser()->getId());

                $this->redirect()
                    ->withMessage('hideDialogSuccess', 'success')
                    ->to(['action' => 'dialog']);
            }
        }

        $this->redirect(['action' => 'dialog']);
    }

    public function dialogmessageAction()
    {
        if ($this->getRequest()->isPost('fetch')) {
            $dialogMapper = new DialogMapper();
            $c_id = $this->getRequest()->getParam('id');

            $dialog = $dialogMapper->getReadLastOneDialog($c_id);
            if ($dialog and $dialog->getUserOne() != $this->getUser()->getId()) {
                $model = new DialogModel();
                $model->setCrId($dialog->getCrId())
                    ->setRead(1);
                $dialogMapper->updateRead($model);
            }

            $this->getView()->set('inbox', $dialogMapper->getDialogMessage($c_id));
        }
    }

    public function deletedialogmessageAction()
    {
        $id = $this->getRequest()->getParam('id');
        $dialogMapper = new DialogMapper();

        // Check if the current user is the autor of the message.
        if ($dialogMapper->isMessageOfUser($id, $this->getUser()->getId())) {
            $dialogMapper->deleteMessageOfUser($id, $this->getUser()->getId());
        }
    }

    public function dialognewAction()
    {
        $DialogMapper = new DialogMapper();
        $ilchdate = new IlchDate;

        $user_one = $this->getUser()->getId();
        $user_two = $this->getRequest()->getParam('id');

        if ($user_one != $user_two) {
            $c_exist = $DialogMapper->getDialogCheck($user_one, $user_two);
            if ($c_exist == null) {
                $model = new DialogModel();
                $model->setUserOne($user_one)
                    ->setUserTwo($user_two)
                    ->setTime($ilchdate->toDb());
                $DialogMapper->save($model);

                $c_id = $DialogMapper->getDialogId($user_one);
                $this->redirect(['action' => 'dialog', 'id' => $c_id->getCId()]);
            }

            $this->redirect(['action' => 'dialog', 'id' => $c_exist->getCId()]);
        }
    }

    public function galleryAction()
    {
        $galleryMapper = new GalleryMapper();
        $imageMapper = new GalleryImageMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGallery'), ['controller' => 'panel', 'action' => 'gallery']);

        /*
         * Saves the item tree to database.
         */
        if ($this->getRequest()->getPost('saveGallery')) {
            $sortItems = json_decode($this->getRequest()->getPost('hiddenMenu'));
            $items = $this->getRequest()->getPost('items');
            $oldItems = $galleryMapper->getGalleryItems($this->getUser()->getId());

            /*
             * Deletes old entries from database.
             */
            if (!empty($oldItems)) {
                foreach ($oldItems as $oldItem) {
                    if (!isset($items[$oldItem->getId()])) {
                        $galleryMapper->deleteItem($oldItem);
                    }
                }
            }

            if ($items) {
                $sortArray = [];

                foreach ($sortItems as $sortItem) {
                    if ($sortItem->item_id !== null) {
                        $sortArray[$sortItem->item_id] = (int)$sortItem->parent_id;
                    }
                }

                foreach ($items as $item) {
                    $galleryItem = new GalleryItemModel();

                    if (strpos($item['id'], 'tmp_') !== false) {
                        $tmpId = str_replace('tmp_', '', $item['id']);
                    } else {
                        $galleryItem->setId($item['id']);
                    }

                    $galleryItem->setUserId($this->getUser()->getId())
                        ->setType($item['type'])
                        ->setTitle($item['title'])
                        ->setDesc($item['desc']);
                    $newId = $galleryMapper->saveItem($galleryItem);

                    if (isset($tmpId)) {
                        foreach ($sortArray as $id => $parentId) {
                            if ($id == $tmpId) {
                                unset($sortArray[$id]);
                                $sortArray[$newId] = $parentId;
                            }

                            if ($parentId == $tmpId) {
                                $sortArray[$id] = $newId;
                            }
                        }
                    }
                }

                $sort = 0;

                foreach ($sortArray as $id => $parent) {
                    $galleryItem = new GalleryItemModel();
                    $galleryItem->setId($id)
                        ->setSort($sort)
                        ->setParentId($parent);
                    $galleryMapper->saveItem($galleryItem);
                    $sort += 10;
                }
            }

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'gallery']);
        }

        $this->getView()->set('galleryItems', $galleryMapper->getGalleryItemsByParent($this->getUser()->getId(), 0))
            ->set('galleryMapper', $galleryMapper)
            ->set('imageMapper', $imageMapper);
    }

    public function friendsAction()
    {
        $friendsMapper = new FriendsMapper();

        $this->getView()->set('friends', $friendsMapper->getFriendsByUserId($this->getUser()->getId()));
    }

    public function sendFriendRequestAction()
    {
        $id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->isSecure()) {
            $friendsMapper = new FriendsMapper();

            $friendsMapper->addFriend($this->getUser()->getId(), $id);
            $this->addMessage('sendFriendRequestSuccess');
        } else {
            $this->addMessage('sendFriendRequestFail', 'warning');
        }

        $this->redirect(['controller' => 'profil', 'action' => 'index', 'user' => $id]);
    }

    public function approveFriendRequestAction()
    {
        if ($this->getRequest()->isSecure()) {
            $friendsMapper = new FriendsMapper();

            $friendsMapper->approveFriendRequest($this->getUser()->getId(), $this->getRequest()->getParam('id'));
            $friendsMapper->addFriend($this->getUser()->getId(), $this->getRequest()->getParam('id'), 1);
            $this->addMessage('approveFriendRequestSuccess');
        } else {
            $this->addMessage('approveFriendRequestFail', 'warning');
        }

        $this->redirect(['controller' => 'panel', 'action' => 'index']);
    }

    public function removeFriendAction()
    {
        $id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->isSecure()) {
            $friendsMapper = new FriendsMapper();

            $friendsMapper->deleteFriendByFriendUserId($id);
            $friendsMapper->deleteFriendOfUser($id, $this->getUser()->getId());
            $this->addMessage('removeFriendSuccess');
            $this->redirect(['controller' => 'panel', 'action' => 'index']);
        } else {
            $this->addMessage('removeFriendFail', 'warning');
            $this->redirect(['controller' => 'profil', 'action' => 'index', 'user' => $id]);
        }
    }

    public function treatGalleryAction() 
    {
        $imageMapper = new GalleryImageMapper();
        $pagination = new \Ilch\Pagination();
        $galleryMapper = new GalleryMapper();

        $id = $this->getRequest()->getParam('id');
        $gallery = $galleryMapper->getGalleryById($id);

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGallery'), ['controller' => 'panel', 'action' => 'gallery'])
            ->add($gallery->getTitle(), ['controller' => 'panel', 'action' => 'treatgallery', 'id' => $id]);

        if ($this->getRequest()->getPost('action') == 'delete') {
            $mediaMapper = new MediaMapper();
            
            foreach ($this->getRequest()->getPost('check_gallery') as $imageId) {
                $mediaMapper->delMediaById($imageId);
            }

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'treatgallery','id' => $id]);
        }

        if ($this->getRequest()->getPost()) {
            foreach ($this->getRequest()->getPost('check_image') as $imageId ) {
                $catId = $this->getRequest()->getParam('id');

                $model = new GalleryImageModel();
                $model->setUserId($this->getUser()->getId());
                $model->setImageId($imageId);
                $model->setCat($catId);
                $imageMapper->save($model);
            }
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('user_picturesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('user_picturesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));
        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination))
            ->set('pagination', $pagination)
            ->set('galleryTitle', $gallery->getTitle());
    }

    public function treatGalleryImageAction() 
    {
        $imageMapper = new GalleryImageMapper();
        $galleryMapper = new GalleryMapper();

        $id = (int)$this->getRequest()->getParam('id');
        $galleryId = (int)$this->getRequest()->getParam('gallery');
        $gallery = $galleryMapper->getGalleryById($galleryId);

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuGallery'), ['controller' => 'panel', 'action' => 'gallery'])
            ->add($gallery->getTitle(), ['controller' => 'panel', 'action' => 'treatgallery', 'id' => $galleryId])
            ->add($this->getTranslator()->trans('treatImage'), ['action' => 'treatgalleryimage', 'gallery' => $galleryId, 'id' => $id]);

        if ($this->getRequest()->getPost()) {
            $imageTitle = $this->getRequest()->getPost('imageTitle');
            $imageDesc = $this->getRequest()->getPost('imageDesc');

            $model = new GalleryImageModel();
            $model->setId($id);
            $model->setImageTitle($imageTitle);
            $model->setImageDesc($imageDesc);
            $imageMapper->saveImageTreat($model);

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('image', $imageMapper->getImageById($id));
    }

    public function delGalleryImageAction()
    {
        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->isSecure()) {
            $mediaMapper->delMediaById($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'treatgallery', 'id' => $this->getRequest()->getParam('gallery')]);
        }
    }

    public function providersAction()
    {
        $authProvider = new AuthProvider();

        $this->getView()->set('authProvider', $authProvider)
            ->set('providers', $authProvider->getProviders());
    }
}
