<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Controllers\Admin;

use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Downloads\Models\File as FileModel;
use Ilch\Validation;

class File extends \Ilch\Controller\Admin
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
                    'name' => 'menuDownloadsBack',
                    'active' => false,
                    'icon' => 'fa fa-arrow-left',
                    'url' => $this->getLayout()->getUrl(['controller' => 'downloads', 'action' => 'treatdownloads', 'id' => $this->getRequest()->getParam('downloads')])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treatdownloads') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
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
            $post = ['fileTitle' => $this->getRequest()->getPost('fileTitle')];
            $validation = Validation::create($post, ['fileTitle' => 'required']);

            if ($validation->isValid()) {
                $fileImage = $this->getRequest()->getPost('fileImage');
                $fileDesc = $this->getRequest()->getPost('fileDesc');

                $model = new FileModel();
                $model->setId($id);
                $model->setFileImage($fileImage);
                $model->setFileTitle($post['fileTitle']);
                $model->setFileDesc($fileDesc);
                $fileMapper->saveFileTreat($model);

                $this->addMessage('saveSuccess');
            }
            $this->getView()->set('errors', $validation->getErrorBag()->getErrorMessages());
            $errorFields = $validation->getFieldsWithError();
        }

        $this->getView()->set('errorFields', (isset($errorFields) ? $errorFields : []));
        $this->getView()->set('file', $fileMapper->getFileById($id));
    }
}
