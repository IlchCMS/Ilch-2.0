<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Models\ProfileField as ProfileFieldModel;
use Modules\User\Mappers\ProfileFieldsTranslation as ProfileFieldsTranslationMapper;

class ProfileFields extends \Ilch\Controller\Admin
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
                'name' => 'menuGroup',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuProfileFields',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'index']),
                [
                    'name' => 'menuActionNewProfileField',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'treat'])
                ]
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
            $items[2][0]['active'] = true;
        } else {
            $items[2]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuUser',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuProfileFields'), ['action' => 'index']);

        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $this->getView()->set('profileFields', $profileFieldsMapper->getProfileFields())
            ->set('profileFieldsTranslation', $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale()));

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_users')) {
            foreach ($this->getRequest()->getPost('check_users') as $id) {
                $profileFieldsMapper->deleteProfileField($id);
                $profileFieldsContentMapper->deleteProfileFieldContentByFieldId($id);
                $profileFieldsTranslationMapper->deleteProfileFieldTranslationsByFieldId($id);
            }
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $positions = explode(',', $postData['hiddenMenu']);
            for ($x = 0; $x < count($positions); $x++) {
                $profileFieldsMapper->updatePositionById($positions[$x], $x);
            }
            $this->addMessage('success');
            $this->redirect(['action' => 'index']);
        }
    }

    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuUser'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuProfileFields'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('editProfileField'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);

        $profileFieldId = $this->getRequest()->getParam('id');
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        if ($profileFieldsMapper->profileFieldWithIdExists($profileFieldId)) {
            $profileField = $profileFieldsMapper->getProfileFieldById($profileFieldId);
        } else {
            $profileField = new ProfileFieldModel();
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $profileFieldData = $postData['profileField'];
            if ($profileFieldData['type'] != 2) {
                $profileFieldData['icon'] = '';
                $profileFieldData['addition'] = '';
            }

            $profileField = $profileFieldsMapper->loadFromArray($profileFieldData);
            $profileFieldId = $profileFieldsMapper->save($profileField);

            for ($i = 0; $i < count($postData); $i++) {
                if (isset($postData['profileFieldTrans'.$i])) {
                    $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

                    $profileFieldTransData = $postData['profileFieldTrans'.$i];
                    if (empty($profileFieldTransData['field_id'])) {
                        $profileFieldTransData['field_id'] = $profileFieldId;
                    }

                    $profileFieldTrans = $profileFieldsTranslationMapper->loadFromArray($profileFieldTransData);
                    if ($profileFieldTrans->getName() != '') {
                        $profileFieldsTranslationMapper->save($profileFieldTrans);
                    } else {
                        $profileFieldsTranslationMapper->deleteProfileFieldTranslation($profileFieldTransData['locale'], $profileFieldTransData['field_id']);
                    }
                }
            }

            if (!empty($profileFieldId)) {
                if (empty($profileFieldData['id'])) {
                    $this->redirect()
                        ->withMessage('newProfileFieldMsg')
                        ->to(['action' => 'index']);
                } else {
                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(['action' => 'index']);
                }
            }

            $this->redirect(['action' => 'treat', 'id' => $profileFieldId]);
        }

        $this->getView()->set('countOfProfileFields', $profileFieldsMapper->getCountOfProfileFields())
            ->set('profileField', $profileField)
            ->set('profileFieldsTranslation', $profileFieldsTranslationMapper->getProfileFieldTranslationByFieldId($profileFieldId))
            ->set('localeList', $this->getTranslator()->getLocaleList());
    }

    public function deleteAction()
    {
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $id = $this->getRequest()->getParam('id');

        if ($id && $this->getRequest()->isSecure()) {
            $profileFieldsMapper->deleteProfileField($id);
            $profileFieldsContentMapper->deleteProfileFieldContentByFieldId($id);
            $profileFieldsTranslationMapper->deleteProfileFieldTranslationsByFieldId($id);
        }

        $this->redirect(['action' => 'index']);
    }
}
