<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Rule\Controllers\Admin;

use Modules\Rule\Mappers\Rule as RuleMapper;
use Modules\Rule\Models\Rule as RuleModel;
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

        $rules = $ruleMapper->getRules();

        $this->getView()->set('rules', $rules);
    }

    public function treatAction() 
    {
        $ruleMapper = new RuleMapper();

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

        $post = [
            'title' => '',
            'text' => '',
            'paragraph' => '',
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'title' => trim($this->getRequest()->getPost('title')),
                'text' => trim($this->getRequest()->getPost('text')),
                'paragraph' => $this->getRequest()->getPost('paragraph'),
            ];

            $validation = Validation::create($post, [
                'title' => 'required',
                'text' => 'required',
                'paragraph' => 'required|numeric|integer|min:1',
            ]);

            if ($validation->isValid()) {
                $model = new RuleModel();

                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }

                $model->setParagraph($post['paragraph']);
                $model->setTitle($post['title']);
                $model->setText($post['text']);
                $ruleMapper->save($model);

                $this->addMessage('saveSuccess');

                $this->redirect(['action' => 'index']);
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
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
