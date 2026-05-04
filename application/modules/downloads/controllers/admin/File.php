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
use Modules\User\Mappers\Group as UserGroupMapper;

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

    public function treatFileAction()
    {
        $fileMapper = new FileMapper();
        $userGroupMapper = new UserGroupMapper();
        $id = (int)$this->getRequest()->getParam('id');
        $downloadsId = (int)$this->getRequest()->getParam('downloads');
        $file = $fileMapper->getFileById($id);

        if (!$file) {
            $this->addMessage('fileNotFound', 'danger');
            $this->redirect(['controller' => 'downloads', 'action' => 'treatdownloads', 'id' => $downloadsId]);
        }

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('downloads'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getLayout()->escape($file->getFileTitle()), ['action' => 'treatfile', 'downloads' => $downloadsId, 'id' => $id]);

        if ($this->getRequest()->getPost()) {
            $fileImage = '';
            if (!empty($this->getRequest()->getPost('fileImage'))) {
                $fileImage = BASE_URL . '/' . $this->getRequest()->getPost('fileImage');
            }

            $post = [
                'fileTitle' => $this->getRequest()->getPost('fileTitle'),
                'fileImage' => $fileImage,
                'fileDesc' => $this->getRequest()->getPost('fileDesc'),
                'access' => $this->getRequest()->getPost('access')
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
                $model->setAccess(implode(',', $this->getRequest()->getPost('access') ?? []));
                $fileMapper->saveFileTreat($model);

                $this->addMessage('saveSuccess');
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }

            $this->redirect(['action' => 'treatfile', 'downloads' => $downloadsId, 'id' => $id]);
        }

        $this->getView()->set('file', $file);
        $this->getView()->set('userGroupList', $userGroupMapper->getGroupList());
    }
}
