<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Rule\Controllers\Admin;

use Modules\Rule\Mappers\Rule as RuleMapper;
use Modules\Rule\Models\Rule as RuleModel;
use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Cats extends \Ilch\Controller\Admin
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
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'cats' AND $this->getRequest()->getActionName() == 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuRules',
            $items
        );
    }

    public function indexAction() 
    {
        $ruleMapper = new RuleMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuRules'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('saveCats')) {
            foreach ($this->getRequest()->getPost('items') as $i => $catId) {
                $ruleMapper->sort($catId, $i);
            }

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        if ($this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_cats') as $catId) {
                $ruleMapper->delete($catId);
            }

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('cats', $ruleMapper->getRules(['r.parent_id' => 0]));
    }

    public function treatAction() 
    {
        $ruleMapper = new RuleMapper();
        $userGroupMapper = new UserGroupMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuRules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('cat', $ruleMapper->getRuleById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuRules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuCats'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'paragraph' => 'required',
                'name'  => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new RuleModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }

                $groups = '';
                if (!empty($this->getRequest()->getPost('groups'))) {
                    $groups = implode(',', $this->getRequest()->getPost('groups'));
                }

                $model->setParagraph($this->getRequest()->getPost('paragraph'));
                $model->setTitle($this->getRequest()->getPost('name'));
                $model->setAccess($groups);
                $ruleMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
        }

        if (!empty($rule)) {
            $groups = explode(',', $rule->getAccess());
        } else {
            $groups = [1,2,3];
        }

        $this->getView()->set('userGroupList', $userGroupMapper->getGroupList());
        $this->getView()->set('groups', $groups);
    }

    public function delCatAction()
    {
        if ($this->getRequest()->isSecure()) {
            $ruleMapper = new RuleMapper();

            if ($ruleMapper->getRulesItemsByParent($this->getRequest()->getParam('id')) == '') {
                $ruleMapper->delete($this->getRequest()->getParam('id'));

                $this->redirect()
                    ->withMessage('deleteSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->redirect()
                    ->withMessage('categoryInUse', 'danger')
                    ->to(['action' => 'index']);
            }
        }
    }
}
