<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Controllers\Admin;

use Ilch\Validation;

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
                'name' => 'menuSettings',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuTraining',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTraining'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        $post = [
            'boxNexttrainingLimit' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'boxNexttrainingLimit' => $this->getRequest()->getPost('boxNexttrainingLimit')
            ];

            $validation = Validation::create($post, [
                'boxNexttrainingLimit' => 'numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('training_boxNexttrainingLimit', $post['boxNexttrainingLimit']);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('post', $post)
            ->set('errorFields', (isset($errorFields) ? $errorFields : []))
            ->set('boxNexttrainingLimit', $this->getConfig()->get('training_boxNexttrainingLimit'));
    }
}
