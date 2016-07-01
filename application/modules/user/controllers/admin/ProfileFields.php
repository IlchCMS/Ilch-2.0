<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Controllers\Admin\Base as BaseController;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Models\ProfileField as ProfileFieldModel;

class ProfileFields extends BaseController
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            [
                'name' => 'menuActionNewProfileField',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'treat', 'id' => 0])
            ]
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuProfileFields'), ['action' => 'index']);

        $profileFieldsMapper = new ProfileFieldsMapper();

        $this->getView()->set('profileFields', $profileFieldsMapper->getProfileFields());
    }

    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuUser'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuProfileFields'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('editProfileField'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);

        $profileFieldId = $this->getRequest()->getParam('id');
        $profileFieldsMapper = new ProfileFieldsMapper();

        if ($profileFieldsMapper->profileFieldWithIdExists($profileFieldId)) {
            $profileField = $profileFieldsMapper->getProfileFieldById($profileFieldId);
        } else {
            $profileField = new ProfileFieldModel();
        }

        $this->getView()->set('profileField', $profileField);
    }

    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();

        if (isset($postData['profileField'])) {
            $profileFieldData = $postData['profileField'];

            $profileFieldsMapper = new ProfileFieldsMapper();
            $profileField = $profileFieldsMapper->loadFromArray($profileFieldData);
            $profileFieldId = $profileFieldsMapper->save($profileField);

            if (!empty($profileFieldId) && empty($profileFieldData['id'])) {
                $this->addMessage('newProfileFieldMsg');
            }

            $this->redirect(['action' => 'treat', 'id' => $profileFieldId]);
        }
    }

    public function deleteAction()
    {
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();

        $id = $this->getRequest()->getParam('id');

        if($id && $this->getRequest()->isSecure()) {
            $profileFieldsMapper->deleteProfileField($id);
            $profileFieldsContentMapper->deleteProfileFieldContentByFieldId($id);
        }

        $this->redirect(['action' => 'index']);
    }
}
