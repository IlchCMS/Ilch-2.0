<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Downloads\Models\File as FileModel;
use Ilch\Validation;

class File extends Admin
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
                    'name' => 'menuDownloadsBack',
                    'active' => false,
                    'icon' => 'fa-solid fa-arrow-left',
                    'url' => $this->getLayout()->getUrl(['controller' => 'downloads', 'action' => 'treatdownloads', 'id' => $this->getRequest()->getParam('downloads')])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treatdownloads') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuDownloads',
            $items
        );
    }

    public function indexAction()
    {
    }

    public function treatFileAction()
    {
        $fileMapper = new FileMapper();
        $id = (int)$this->getRequest()->getParam('id');

        if ($this->getRequest()->getPost()) {
            $fileImage = '';
            if (!empty($this->getRequest()->getPost('fileImage'))) {
                $fileImage = BASE_URL . '/' . $this->getRequest()->getPost('fileImage');
            }

            $post = [
                'fileTitle' => $this->getRequest()->getPost('fileTitle'),
                'fileImage' => $fileImage,
                'fileDesc' => $this->getRequest()->getPost('fileDesc')
            ];

            $validation = Validation::create($post, ['fileTitle' => 'required', 'fileImage' => 'url']);

            if ($validation->isValid()) {
                $fileImage = $this->getRequest()->getPost('fileImage');
                $fileDesc = $this->getRequest()->getPost('fileDesc');

                $model = new FileModel();
                $model->setId($id);
                $model->setFileImage($fileImage);
                $model->setFileTitle($this->getRequest()->getPost('fileTitle'));
                $model->setFileDesc($fileDesc);
                $fileMapper->saveFileTreat($model);

                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('file', $fileMapper->getFileById($id));
    }
}
