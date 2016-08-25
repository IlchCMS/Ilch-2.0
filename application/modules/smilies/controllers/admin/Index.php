<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Smilies\Controllers\Admin;

use Modules\Smilies\Mappers\Smilies as SmiliesMapper;
use Modules\Smilies\Models\Smilies as SmiliesModel;
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
                    'name' => 'upload',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'upload'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getControllerName() == 'index' AND $this->getRequest()->getActionName() == 'upload') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuSmilies',
            $items
        );
    }

    public function indexAction()
    {
        $smiliesMapper = new SmiliesMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSmilies'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $id) {
                    $smiliesMapper->delete($id);
                }
            }
        }

        $this->getView()->set('smilies', $smiliesMapper->getSmilies());
    }

    public function treatAction()
    {
        $smiliesMapper = new SmiliesMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuSmilies'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('smilie', $smiliesMapper->getSmilieById($this->getRequest()->getParam('id')));
        }

        $post = [
            'name' => '',
            'url' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $url = trim($this->getRequest()->getPost('url'));
            if (!empty($url)) {
                $url = BASE_URL.'/'.$url;
            }

            $post = [
                'name' => trim($this->getRequest()->getPost('name')),
                'url' => $url
            ];

            Validation::setCustomFieldAliases([
                'url' => 'image'
            ]);

            $validation = Validation::create($post, [
                'name' => 'required',
                'url' => 'required|url'
            ]);

            $post['url'] = trim($this->getRequest()->getPost('url'));

            if ($validation->isValid()) {
                $model = new SmiliesModel();

                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }

                $model->setName($post['name']);
                $model->setUrl($post['url']);
                $smiliesMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }

            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $smiliesMapper = new SmiliesMapper();
            $smiliesMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function uploadAction() 
    {
        $smiliesMapper = new SmiliesMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuSmilies'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('upload'), ['action' => 'upload']);

        $allowedExtensions = $this->getConfig()->get('smiley_filetypes');

        $this->getView()->set('allowedExtensions', $allowedExtensions);

        if (!is_writable(APPLICATION_PATH.'/modules/smilies/static/img/')) {
            $this->addMessage('writableMedia', 'danger');
        }

        if ($this->getRequest()->isPost()) {
            $upload = new \Ilch\Upload();
            $upload->setFile($_FILES['upl']['name']);
            $upload->setTypes($this->getConfig()->get('smiley_filetypes'));
            $upload->setPath('application/modules/smilies/static/img/');
            // Early return if extension is not allowed. Should normally already be done client-side.
            $upload->setAllowedExtensions($allowedExtensions);
            if (!file_exists($_FILES['upl']['tmp_name']) || !$upload->isAllowedExtension()) {
                return;
            }
            $upload->upload();

            $model = new SmiliesModel();
            $model->setName($upload->getName());
            $model->setUrl($upload->getUrl());
            $model->setUrlThumb($upload->getUrlThumb());
            $model->setEnding($upload->getEnding());
            $smiliesMapper->saveUpload($model);
        }
    }
}
