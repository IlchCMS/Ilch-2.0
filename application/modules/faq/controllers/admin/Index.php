<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Faq\Controllers\Admin;

use Modules\Faq\Mappers\Faq as FaqMapper;
use Modules\Faq\Models\Faq as FaqModel;
use Modules\Faq\Mappers\Category as CategoryMapper;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuFaqs',
            array
            (
                array
                (
                    'name' => 'menuFaqs',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'menuCats',
                    'active' => false,
                    'icon' => 'fa fa-th-list',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'cats', 'action' => 'index'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'add',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $faqMapper = new FaqMapper();
        
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'));
        
        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_faqs')) {
            foreach($this->getRequest()->getPost('check_faqs') as $faqId) {
                $faqMapper->delete($faqId);
            }
        }

        $this->getView()->set('faqs', $faqMapper->getFaqs());
    }

    public function treatAction()
    {
        $categoryMapper = new CategoryMapper();
        $faqMapper = new FaqMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('edit'), array('action' => 'treat'));

            $this->getView()->set('faq', $faqMapper->getFaqById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuFaqs'), array('action' => 'index'))
                    ->add($this->getTranslator()->trans('add'), array('action' => 'treat'));
        }

        if ($this->getRequest()->isPost()) {
            $model = new FaqModel();

            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }

            $question = $this->getRequest()->getPost('question');
            $answer = $this->getRequest()->getPost('answer');

            if (empty($question)) {
                $this->addMessage('missingQuestion', 'danger');
            } elseif(empty($answer)) {
                $this->addMessage('missingAnswer', 'danger');
            } else {
                $model->setQuestion($question);
                $model->setAnswer($answer);
                $model->setCatId($this->getRequest()->getPost('catId'));
                $faqMapper->save($model);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }

        $this->getView()->set('cats', $categoryMapper->getCategories());
    }

    public function delFaqAction()
    {
        if($this->getRequest()->isSecure()) {
            $faqMapper = new FaqMapper();
            $faqMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}
