<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Linkus\Controllers\Admin;

use Modules\Linkus\Mappers\Linkus as LinkusMapper;
use Modules\Linkus\Models\Linkus as LinkusModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuLinkus',
            $items
        );
    }

    public function indexAction()
    {
        $linkusMapper = new LinkusMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinkus'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_linkus') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_linkus') as $linkusId) {
                $linkusMapper->delete($linkusId);
            }
        }

        $this->getView()->set('linkus', $linkusMapper->getLinkus());
    }

    public function treatAction()
    {
        $linkusMapper = new LinkusMapper();
        $model = new LinkusModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuLinkus'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $model = $linkusMapper->getLinkusById($this->getRequest()->getParam('id'));

            if (!$model) {
                $this->redirect()
                    ->withMessage('entryNotFound')
                    ->to(['controller' => 'index', 'action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinkus'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('linkus', $model);

        if ($this->getRequest()->isPost()) {
            // Add BASE_URL to get a complete URL for validation
            $banner = trim($this->getRequest()->getPost('banner'));
            if (!empty($banner)) {
                $banner = BASE_URL . '/' . urlencode($banner);
            }

            $post = [
                'title' => $this->getRequest()->getPost('title', '', true),
                'banner' => $banner,
            ];

            $validation = Validation::create($post, [
                'title' => 'required',
                'banner' => 'required|url',
            ]);

            if ($validation->isValid()) {
                $model->setTitle($this->getRequest()->getPost('title', '', true));
                $model->setBanner($this->getRequest()->getPost('banner', '', true));
                $linkusMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($model->getId() ? ['id' => $model->getId()] : [])));
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure() && !empty($this->getRequest()->getParam('id'))) {
            $linkusMapper = new LinkusMapper();
            $linkusMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
