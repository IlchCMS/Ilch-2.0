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
use Modules\User\Models\Media as MediaModel;
use Ilch\Date as IlchDate;

use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Models\ProfileFieldContent as ProfileFieldContentModel;

class Panel extends BaseController
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index', 'user' => $this->getUser()->getId()]);
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

        $profileFieldsContent = $profileFieldsContentMapper->getProfileFieldContentByUserId($this->getUser()->getId());
        $profileFields = $profileFieldsMapper->getProfileFields();
        $this->getView()->set('profileFieldsContent', $profileFieldsContent);
        $this->getView()->set('profileFields', $profileFields);
        
        $errors = [];
        if ($this->getRequest()->isPost()) {
            $email = trim($this->getRequest()->getPost('email'));
            $firstname = trim($this->getRequest()->getPost('first-name'));
            $lastname = trim($this->getRequest()->getPost('last-name'));
            $homepage = trim($this->getRequest()->getPost('homepage'));
            $facebook = trim($this->getRequest()->getPost('facebook'));
            $twitter = trim($this->getRequest()->getPost('twitter'));
            $google = trim($this->getRequest()->getPost('google'));
            $city = trim($this->getRequest()->getPost('city'));
            $birthday = new \Ilch\Date(trim($this->getRequest()->getPost('birthday')));

            if (empty($email)) {
                $this->addMessage('emailEmpty');
                $this->redirect(['action' => 'profile']);
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->addMessage('emailError');
                $this->redirect(['action' => 'profile']);
            }

            if (empty($errors)) {
                $model = new UserModel();
                $model->setId($this->getUser()->getId());
                $model->setEmail($email);
                $model->setFirstName($firstname);
                $model->setLastName($lastname);
                $model->setHomepage($homepage);
                $model->setFacebook($facebook);
                $model->setTwitter($twitter);
                $model->setGoogle($google);
                $model->setCity($city);
                $model->setBirthday($birthday);
                $profilMapper->save($model);

                foreach ($profileFields as $profileField) {
                    $profileFieldsContent = new ProfileFieldContentModel();
                    $profileFieldsContent->setFieldId($profileField->getId());
                    $profileFieldsContent->setUserId($this->getUser()->getId());
                    $profileFieldsContent->setValue(trim($this->getRequest()->getPost($profileField->getName())));
                    $profileFieldsContentMapper->save($profileFieldsContent);
                }

                $this->redirect(['action' => 'profile']);
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

                if ($file_size <= $avatarSize AND $width <= $avatarWidth AND $height <= $avatarHeight) {
                    $avatar = $path.$this->getUser()->getId().'.'.$endung;

                    if ($this->getUser()->getAvatar() != '') {
                        $settingMapper = new SettingMapper();
                        $settingMapper->delAvatarById($this->getUser()->getId());
                    }

                    $model = new UserModel();
                    $model->setId($this->getUser()->getId());
                    $model->setAvatar($avatar);
                    $profilMapper->save($model);

                    if (move_uploaded_file($file_tmpe, $avatar)) {
                        $this->addMessage('successAvatar');
                    }
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

        $this->getView()->set('settingMapper', $settingMapper);
        $this->getView()->set('avatar_height', $avatarHeight);
        $this->getView()->set('avatar_width', $avatarWidth);
        $this->getView()->set('avatar_size', $avatarSize);
        $this->getView()->set('avatar_filetypes', $avatarAllowedFiletypes);
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
            $model->setId($this->getUser()->getId());
            $model->setSignature(trim($this->getRequest()->getPost('signature')));
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
            $password = trim($this->getRequest()->getPost('password'));
            $password2 = trim($this->getRequest()->getPost('password2'));

            if (empty($password)) {
                $this->addMessage('passwordEmpty', $type = 'danger');
                $this->redirect(['action' => 'password']);
            } elseif (empty($password2)) {
                $this->addMessage('passwordRetypeEmpty', $type = 'danger');
                $this->redirect(['action' => 'password']);
            } elseif (strlen($password) < 6 OR strlen($password) > 30) {
                $this->addMessage('passwordLength', $type = 'danger');
                $this->redirect(['action' => 'password']);
            } elseif ($password != $password2) {
                $this->addMessage('passwordNotEqual', $type = 'danger');
                $this->redirect(['action' => 'password']);
            }

            if (!empty($password) AND !empty($password2) AND $password == $password2) {
                // Delete all stored authTokens of a user when he changes his password.
                // This will invalidate all possibly stolen rememberMe-cookies.
                $authTokenMapper = new \Modules\User\Mappers\AuthToken();
                $authTokenMapper->deleteAllAuthTokenOfUser($this->getUser()->getId());

                $password = (new PasswordService())->hash($password);

                $model = new UserModel();
                $model->setId($this->getUser()->getId());
                $model->setPassword($password);
                $profilMapper->save($model);

                $this->addMessage('passwordSuccess');
                $this->redirect(['action' => 'password']);
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
            $model = new UserModel();
            $model->setId($this->getUser()->getId());
            $model->setOptMail($this->getRequest()->getPost('opt_mail'));
            $profilMapper->save($model);

            $this->redirect(['action' => 'setting']);
        }
    }

    public function dialogAction()
    {
        $dialogMapper = new DialogMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPanel'), ['controller' => 'panel', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuDialog'), ['controller' => 'panel', 'action' => 'dialog']);

        $this->getView()->set('dialog', $dialogMapper->getDialog($this->getUser()->getId()));
    }

    public function dialogviewmessageAction()
    {
        if ($this->getRequest()->isPost('fetch')) {
            $dialogMapper = new DialogMapper();
            $c_id = $this->getRequest()->getParam('id');
            $user = $dialogMapper->getDialogCheckByCId($c_id);

            if ($this->getUser()->getId() != $user->getUserTwo()) {
                $user_two = $user->getUserOne();
            } else {
                $user_two = $user->getUserTwo();
            }

            $dialog = $dialogMapper->getReadLastOneDialog($c_id);
            if ($dialog and $dialog->getUserOne() != $this->getUser()->getId()) {
                $model = new DialogModel();
                $model->setCrId($dialog->getCrId());
                $model->setRead(1);
                $dialogMapper->updateRead($model);
            }

            $this->getView()->set('inbox', $dialogMapper->getDialogMessage($c_id));
        }
    }

    public function dialogviewAction()
    {
        $DialogMapper = new DialogMapper();
        $ilchdate = new IlchDate;

        $c_id = $this->getRequest()->getParam('id');
        $user = $DialogMapper->getDialogCheckByCId($c_id);

        if ($this->getUser()->getId() != $user->getUserTwo()) {
            $user_two = $user->getUserOne();
        } else {
            $user_two = $user->getUserTwo();
        }

        if ($this->getUser()->getId() == $user_two) {
            if ($this->getRequest()->isPost()) {

                $u_id_fk = $this->getUser()->getId();
                $text = trim($this->getRequest()->getPost('text'));

                $model = new DialogModel();
                $model->setCId($c_id);
                $model->setId($u_id_fk);
                $model->setTime($ilchdate->toDb());
                $model->setText($text);
                $DialogMapper->save($model);

                $this->redirect(['action' => 'dialogview','id'=> $c_id]);
            }

            $this->getView()->set('inbox', $DialogMapper->getDialogMessage($c_id));

            $dialog = $DialogMapper->getReadLastOneDialog($c_id);
            if ($dialog and $dialog->getUserOne() != $this->getUser()->getId()) {
                $model = new DialogModel();
                $model->setCrId($dialog->getCrId());
                $model->setRead(1);
                $DialogMapper->updateRead($model);
            }

        } else {
            $this->redirect(['action' => 'dialog']);
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
                $model->setUserOne($user_one);
                $model->setUserTwo($user_two);
                $model->setTime($ilchdate->toDb());
                $DialogMapper->save($model);

                $c_id = $DialogMapper->getDialogId($user_one);
                $this->redirect(['action' => 'dialogview', 'id' => $c_id->getCId()]);
            }

            $this->redirect(['action' => 'dialogview', 'id' => $c_exist->getCId()]);
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
            $oldItems = $galleryMapper->getGalleryItems(1);

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

                    $galleryItem->setGalleryId(1);
                    $galleryItem->setUserId($this->getUser()->getId());
                    $galleryItem->setType($item['type']);
                    $galleryItem->setTitle($item['title']);
                    $galleryItem->setDesc($item['desc']);
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
                    $galleryItem->setId($id);
                    $galleryItem->setSort($sort);
                    $galleryItem->setParentId($parent);
                    $galleryMapper->saveItem($galleryItem);
                    $sort += 10;
                }
            }

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'gallery']);
        }

        $this->getView()->set('galleryItems', $galleryMapper->getGalleryItemsByParent($this->getUser()->getId(), 1, 0));
        $this->getView()->set('galleryMapper', $galleryMapper);
        $this->getView()->set('imageMapper', $imageMapper);
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
            foreach ($this->getRequest()->getPost('check_gallery') as $imageId) {
                $imageMapper->deleteById($imageId);
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

        $pagination->setRowsPerPage(empty($this->getConfig()->get('user_picturesPerPage')) ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('user_picturesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));
        $this->getView()->set('image', $imageMapper->getImageByGalleryId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('galleryTitle', $gallery->getTitle());
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
}
