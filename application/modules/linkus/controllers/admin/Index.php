<?php
/**
 * @copyright Ilch 2.0
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
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
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

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinkus'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('linkus', $linkusMapper->getLinkusById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuLinkus'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'title' => '',
            'banner' => '',
        ];

        if ($this->getRequest()->isPost()) {
            // Add BASE_URL to get a complete URL for validation
            $banner = trim($this->getRequest()->getPost('banner'));
            if (!empty($banner)) {
                $banner = BASE_URL.'/'.urlencode($banner);
            }

            $post = [
                'title' => trim($this->getRequest()->getPost('title')),
                'banner' => $banner,
            ];

            $validation = Validation::create($post, [
                'title' => 'required',
                'banner' => 'required|url',
            ]);

            $post['banner'] = trim($this->getRequest()->getPost('banner'));

            if ($validation->isValid()) {
                $model = new LinkusModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }
                $model->setTitle($post['title']);
                $model->setBanner($post['banner']);
                $linkusMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('post', $post);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $linkusMapper = new LinkusMapper();
            $linkusMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
