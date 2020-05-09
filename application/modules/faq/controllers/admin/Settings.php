<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Controllers\Admin;

use Ilch\Validation;
use Modules\Faq\Mappers\Category as CategoryMapper;
use Modules\Faq\Models\Category as CategoryModel;
use Modules\Faq\Mappers\Faq as FaqMapper;
use Modules\User\Mappers\Group as GroupMapper;

class Settings extends \Ilch\Controller\Admin
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
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[1][0]['active'] = true;
        } elseif ($this->getRequest()->getControllerName() === 'settings') {
            $items[2]['active'] = true;
        } else {
            $items[1]['active'] = true;
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
                ->add($this->getTranslator()->trans('menuFaqs'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuSettings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'sortCategoriesAlphabetically' => 'required|numeric|integer|min:0|max:1',
                'sortQuestionsAlphabetically' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('faq_sortCategoriesAlphabetically', $this->getRequest()->getPost('sortCategoriesAlphabetically'));
                $this->getConfig()->set('faq_sortQuestionsAlphabetically', $this->getRequest()->getPost('sortQuestionsAlphabetically'));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->redirect()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('sortCategoriesAlphabetically', $this->getConfig()->get('faq_sortCategoriesAlphabetically'));
        $this->getView()->set('sortQuestionsAlphabetically', $this->getConfig()->get('faq_sortQuestionsAlphabetically'));
    }
}
