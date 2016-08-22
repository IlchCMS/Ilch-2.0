<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Controllers\Admin;

use Modules\Faq\Mappers\Faq as FaqMapper;
use Modules\Faq\Models\Faq as FaqModel;
use Modules\Faq\Mappers\Category as CategoryMapper;
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
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuFaqs',
            $items
        );
    }

    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_faqs')) {
            foreach ($this->getRequest()->getPost('check_faqs') as $faqId) {
                $faqMapper->delete($faqId);
            }
        }

        $this->getView()->set('categoryMapper', $categoryMapper);
        $this->getView()->set('faqs', $faqMapper->getFaqs());
    }

    public function treatAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('faq', $faqMapper->getFaqById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'catId' => '',
            'question' => '',
            'answer' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'catId' => $this->getRequest()->getPost('catId'),
                'question' => $this->getRequest()->getPost('question'),
                'answer' => $this->getRequest()->getPost('answer')
            ];

            $validation = Validation::create($post, [
                'catId' => 'required|numeric|integer|min:0',
                'question' => 'required',
                'answer' => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new FaqModel();

                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }

                $model->setQuestion($post['question']);
                $model->setAnswer($post['answer']);
                $model->setCatId($post['catId']);
                $faqMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post);
        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('cats', $categoryMapper->getCategories());
    }

    public function delFaqAction()
    {
        if ($this->getRequest()->isSecure()) {
            $faqMapper = new FaqMapper();
            $faqMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
