<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Imprint\Controllers\Admin;

use Ilch\Validation;
use Modules\Imprint\Mappers\Imprint as ImprintMapper;
use Modules\Imprint\Models\Imprint as ImprintModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa-solid fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuImprint',
            $items
        );
    }

    public function indexAction()
    {
        $imprintMapper = new ImprintMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuImprint'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        $model = $imprintMapper->getImprintById(1);
        if (!$model) {
            $model = new ImprintModel();
            $model->setId(1);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'imprint' => 'required',
            ]);

            if ($validation->isValid()) {
                $model->setImprint($this->getRequest()->getPost('imprint'));
                $imprintMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'index']);
            }
        }

        $this->getView()->set('imprint', $model);
    }
}
