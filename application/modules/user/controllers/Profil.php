<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Gallery as GalleryMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Mappers\ProfileFieldsTranslation as ProfileFieldsTranslationMapper;

class Profil extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $userMapper = new UserMapper();
        $galleryMapper = new GalleryMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $profil = $userMapper->getUserById($this->getRequest()->getParam('user'));
        $profileFieldsContent = $profileFieldsContentMapper->getProfileFieldContentByUserId($this->getRequest()->getParam('user'));
        $profileFields = $profileFieldsMapper->getProfileFields();
        $profileFieldsTranslation = $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale());

        if ($profil) {
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuUserList'), ['controller' => 'index'])
                    ->add($profil->getName(), ['action' => 'index', 'user' => $this->getRequest()->getParam('user')]);

            $this->getView()->set('userMapper', $userMapper);
            $this->getView()->set('profil', $profil);
            $this->getView()->set('profileFieldsContent', $profileFieldsContent);
            $this->getView()->set('profileFields', $profileFields);
            $this->getView()->set('profileFieldsTranslation', $profileFieldsTranslation);
            $this->getView()->set('galleryAllowed', $this->getConfig()->get('usergallery_allowed'));
            $this->getView()->set('gallery', $galleryMapper->getCountGalleryByUser($this->getRequest()->getParam('user')));
        } else {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'User', 'errorText' => 'notFound']);
        }
    }
}
