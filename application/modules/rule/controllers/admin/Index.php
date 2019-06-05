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
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
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
            ->add($this->getTranslator()->trans('menuRules'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $ruleId) {
                    $ruleMapper->delete($ruleId);
                }
            }
        }

        if ($this->getRequest()->getPost('saveRules')) {
            foreach ($this->getRequest()->getPost('items') as $i => $id) {
                $ruleMapper->sort($id, $i);
            }

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('rules', $ruleMapper->getRules());
    }

    public function treatAction()
    {
        $ruleMapper = new RuleMapper();
        $userGroupMapper = new UserGroupMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuRules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('rule', $ruleMapper->getRuleById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuRules'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'paragraph' => 'required',
                'title' => 'required',
                'text' => 'required',
                'cat' => 'required|integer|min:0'
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

                $model->setParagraph($this->getRequest()->getPost('paragraph'))
                    ->setTitle($this->getRequest()->getPost('title'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->SetParent_Id($this->getRequest()->getPost('cat'))
                    ->setAccess($groups);
                $ruleMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag());

            if ($this->getRequest()->getParam('id')) {
                $this->redirect()
                    ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
            } else {
                $this->redirect()
                    ->to(['action' => 'treat']);
            }
        }

        $this->getView()->set('rulesparents', $ruleMapper->getRules(['r.parent_id' => 0]));
        $this->getView()->set('userGroupList', $userGroupMapper->getGroupList());
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $ruleMapper = new RuleMapper();
            $ruleMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
