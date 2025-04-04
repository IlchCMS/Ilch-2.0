<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Models\ProfileField as ProfileFieldModel;
use Modules\User\Mappers\ProfileFieldsTranslation as ProfileFieldsTranslationMapper;

class ProfileFields extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroup',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuProfileFields',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'index']),
                [
                    'name' => 'menuActionNewProfileField',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuAuthProviders',
                'active' => false,
                'icon' => 'fa-solid fa-key',
                'url'  => $this->getLayout()->getUrl(['controller' => 'providers', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat' && !$this->getRequest()->getParam('id')) {
            $items[2][0]['active'] = true;
        } else {
            $items[2]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuUser',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuUser'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuProfileFields'), ['action' => 'index']);

        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $this->getView()->set('profileFields', $profileFieldsMapper->getProfileFields())
            ->set('profileFieldsTranslation', $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale()));

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_users')) {
            foreach ($this->getRequest()->getPost('check_users') as $id) {
                $profileFieldsMapper->deleteProfileField($id);
                // profile field content and translations get deleted due to FKCs.
            }
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $positions = explode(',', $postData['hiddenMenu']);
            foreach ($positions as $x => $xValue) {
                $profileFieldsMapper->updatePositionById($xValue, $x);
            }
            $this->addMessage('success');
            $this->redirect(['action' => 'index']);
        }
    }

    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuUser'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuProfileFields'), ['action' => 'index']);

        $profileFieldId = $this->getRequest()->getParam('id');
        $multiTypes = [3, 4, 5];

        if (!empty($profileFieldId)) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('editProfileField'), ['action' => 'treat', 'id' => $profileFieldId]);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuActionNewProfileField'), ['action' => 'treat']);
        }

        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        if ($profileFieldId && $profileFieldsMapper->profileFieldWithIdExists($profileFieldId)) {
            $profileField = $profileFieldsMapper->getProfileFieldById($profileFieldId);
        } else {
            $profileField = new ProfileFieldModel();
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            $profileFieldData = $postData['profileField'];
            $profileFieldData['registration'] = $profileFieldData['showOnRegistration'] + $profileFieldData['showOnRegistrationRequired'];
            if ($profileFieldData['type'] != 2) {
                $profileFieldData['icon'] = '';
                $profileFieldData['addition'] = '';
            }

            if (in_array($profileFieldData['type'], $multiTypes)) {
                $profileFieldData['options'] = json_encode(array_filter($postData['profileFieldOptions']));
            } else {
                $profileFieldData['options'] = '';
            }

            $profileField = $profileFieldsMapper->loadFromArray($profileFieldData);
            $profileFieldId = $profileFieldsMapper->save($profileField);

            foreach ($postData['profileFieldTrans_field_id'] as $i => $field_id) {
                $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

                if (empty($field_id)) {
                    $field_id = $profileFieldId;
                }
                $profileFieldTransData = [
                    'field_id' => $field_id,
                    'locale' => $postData['profileFieldTrans_locale'][$i],
                    'name' => $postData['profileFieldTrans_name'][$i],
                ];

                $profileFieldTrans = $profileFieldsTranslationMapper->loadFromArray($profileFieldTransData);
                if ($profileFieldTrans->getName() != '' && $profileFieldTrans->getLocale() != '') {
                    $profileFieldsTranslationMapper->save($profileFieldTrans);
                } else {
                    $profileFieldsTranslationMapper->deleteProfileFieldTranslation($postData['profileFieldTrans_oldLocale'][$i], $profileFieldTransData['field_id']);
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

        $profileFieldsTranslation = ($profileFieldId) ? $profileFieldsTranslationMapper->getProfileFieldTranslationByFieldId($profileFieldId) : [];
        if (count($profileFieldsTranslation) == 0) {
            $profileFieldsTranslation[] = $profileFieldsTranslationMapper->loadFromArray();
        }

        $this->getView()->set('countOfProfileFields', $profileFieldsMapper->getCountOfProfileFields())
            ->set('profileField', $profileField)
            ->set('profileFieldsTranslation', $profileFieldsTranslation)
            ->set('localeList', $this->getTranslator()->getLocaleList())
            ->set('multiTypes', $multiTypes);
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $profileFieldsMapper = new ProfileFieldsMapper();
            $profileFieldsMapper->update($this->getRequest()->getParam('id'));
            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function deleteAction()
    {
        $profileFieldsMapper = new ProfileFieldsMapper();

        $id = $this->getRequest()->getParam('id');
        if ($id && $this->getRequest()->isSecure()) {
            $profileFieldsMapper->deleteProfileField($id);
            // profile field content and translations get deleted due to FKCs.
        }

        $this->redirect(['action' => 'index']);
    }
}
