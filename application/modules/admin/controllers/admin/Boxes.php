<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Box as BoxMapper;
use Modules\Admin\Models\Box as BoxModel;
use Modules\Admin\Mappers\Menu as MenuMapper;
use Ilch\Validation;
use Ilch\Sorter;
use Modules\User\Mappers\Group as GroupMapper;

class Boxes extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'boxes', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'boxes', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuBoxes',
            $items
        );
    }

    public function indexAction()
    {
        $boxMapper = new BoxMapper();
        $menuMapper = New MenuMapper();
        $sorter = New Sorter($this->getRequest(), ['id', 'title', 'date_created']);

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuBoxes'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_boxes')) {
            foreach ($this->getRequest()->getPost('check_boxes') as $boxId) {
                $boxMapper->delete($boxId);
                $menuMapper->deleteItemByBoxId($boxId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $boxes = $boxMapper->getSelfBoxList('', $sorter->getOrderByArray());

        /*
         * Filtering boxes out which are not allowed for the user.
         */
        $user = \Ilch\Registry::get('user');

        foreach ($boxes ?? [] as $key => $box) {
            if (!$user->hasAccess('box_'.$box->getId())) {
                unset($boxes[$key]);
            }
        }

        $this->getView()->set('boxMapper', $boxMapper)
            ->set('boxes', $boxes)
            ->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'))
            ->set('contentLanguage', $this->getConfig()->get('content_language'))
            ->set('sorter', $sorter);
    }

    public function treatAction()
    {
        $groupMapper = new GroupMapper();
        $model = new BoxModel();

        $groups = $groupMapper->getGroupList();
        $boxMapper = new BoxMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuBoxes'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);

            $user = \Ilch\Registry::get('user');

            if (!$user->hasAccess('box_'.$this->getRequest()->getParam('id'))) {
                $this->redirect(['action' => 'index']);
            }

            if ($this->getRequest()->getParam('locale') == '') {
                $locale = '';
            } else {
                $locale = $this->getRequest()->getParam('locale');
            }
            $model = $boxMapper->getSelfBoxByIdLocale($this->getRequest()->getParam('id'), $locale);
            if (!$model) {
                $model = new BoxModel();
            }
            if (!$model->getId()) {
                $model->setId($this->getRequest()->getParam('id'));
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuBoxes'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create(
                $this->getRequest()->getPost(),
                [
                    'boxTitle' => 'required',
                    'boxContent' => 'required'
                ]
            );

            if ($validation->isValid()) {
                $model->setTitle($this->getRequest()->getPost('boxTitle'))
                    ->setContent($this->getRequest()->getPost('boxContent'));
                if ($this->getRequest()->getPost('boxLanguage') != '') {
                    $model->setLocale($this->getRequest()->getPost('boxLanguage'));
                }
                $boxId = $boxMapper->save($model);

                if (!$model->getId()) {
                    foreach ($groups as $key => $group) {
                        if ($group->getId() !== 1) {
                            $groupMapper->saveAccessData($group->getId(), $boxId, 1, 'box');
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
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($model->getId()?['id' => $model->getId()]:[])));
        }

        $this->getView()->set('box', $model)
            ->set('contentLanguage', $this->getConfig()->get('content_language'))
            ->set('languages', $this->getTranslator()->getLocaleList())
            ->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'));
    }

    /**
     * Deleting a box.
     *
     * If the user has no rights to do so, just redirect to index.
     */
    public function deleteAction()
    {
        $user = \Ilch\Registry::get('user');

        if ($user->hasAccess('box_'.$this->getRequest()->getParam('id')) && $this->getRequest()->isSecure()) {
            $boxMapper = new BoxMapper();
            $menuMapper = New MenuMapper();

            $boxMapper->delete($this->getRequest()->getParam('id'));
            $menuMapper->deleteItemByBoxId($this->getRequest()->getParam('id'));
        }

        $this->redirect(['action' => 'index']);
    }
}
