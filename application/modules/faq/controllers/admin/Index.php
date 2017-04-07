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

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'catId' => 'required|numeric|integer|min:1',
                'question' => 'required',
                'answer' => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new FaqModel();

                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }

                $model->setQuestion($this->getRequest()->getPost('question'));
                $model->setAnswer($this->getRequest()->getPost('answer'));
                $model->setCatId($this->getRequest()->getPost('catId'));
                $faqMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                  ->withInput()
                  ->withErrors($validation->getErrorBag())
                  ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
            }
        }

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
