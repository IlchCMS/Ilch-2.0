<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Modules\Media\Mappers\Media as MediaMapper;
use Modules\Media\Models\Media as MediaModel;
use Ilch\Date as IlchDate;

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
                    'name' => 'menuActionAddNew',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url'  => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'upload'])
                ]
            ],
            [
                'name' => 'cats',
                'active' => false,
                'icon' => 'fa fa-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'import',
                'active' => false,
                'icon' => 'fa fa-download',
                'url'  => $this->getLayout()->getUrl(['controller' => 'import', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'upload') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuMedia',
            $items
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['action' => 'index']);

        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_medias') > 0) {
            foreach ($this->getRequest()->getPost('check_medias') as $mediaId) {
                $mediaMapper->delMediaById($mediaId);
            }
            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }

        if ($this->getRequest()->getPost('assignedCategory') && $this->getRequest()->getPost('check_medias') > 0) {
            foreach ($this->getRequest()->getPost('check_medias') as $mediaId) {
                $mediaModel = new MediaModel();
                $mediaModel->setId($mediaId);
                $mediaModel->setCatId($this->getRequest()->getPost('assignedCategory'));
                $mediaMapper->setCat($mediaModel);
            }
            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }

        $pagination = new \Ilch\Pagination();
        $pagination->setRowsPerPage(!$this->getConfig()->get('media_mediaPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('media_mediaPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        if ($this->getRequest()->getParam('rows')) {
            $pagination->setRowsPerPage($this->getRequest()->getParam('rows'));
            $rows = ['rows' => $this->getRequest()->getParam('rows')];
        } else {
            $rows = [];
        }
        $this->getView()->set('rows', $rows);

        if ($this->getRequest()->getPost('search') === 'search') {
            $pagination->setRowsPerPage($this->getRequest()->getPost('rows'));
            $rows = ['rows' => $this->getRequest()->getPost('rows')];
            $this->getView()->set('rows', $rows);

            $sortOrder = 'DESC';
            if ($this->getRequest()->getPost('order') === 'ASC') {
                $sortOrder = 'ASC';
            }

            if ($this->getRequest()->getPost('orderbytype')) {
                switch($this->getRequest()->getPost('orderbytype')) {
                    case 'image':
                        $type = $this->getConfig()->get('media_ext_img');
                        break;
                    case 'video':
                        $type = $this->getConfig()->get('media_ext_video');
                        break;
                    case 'file':
                        $type = $this->getConfig()->get('media_ext_file');
                        break;
                }
            }

            if ($this->getRequest()->getPost('orderbytype') !== 'all') {
                $this->getView()->set('medias', $mediaMapper->getMediaListByEnding($type, $pagination, $sortOrder));
            } else {
                $this->getView()->set('medias', $mediaMapper->getMediaList($pagination, $sortOrder));
            }
        } else {
            $this->getView()->set('medias', $mediaMapper->getMediaList($pagination));
        }

        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('catnames', $mediaMapper->getCatList());
        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function uploadAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('mediaUpload'), ['action' => 'upload']);

        $allowedExtensions = $this->getConfig()->get('media_ext_img').' '.$this->getConfig()->get('media_ext_file').' '.$this->getConfig()->get('media_ext_video');
        $this->getView()->set('allowedExtensions', $allowedExtensions);

        if (!is_writable(ROOT_PATH.'/'.$this->getConfig()->get('media_uploadpath'))) {
            $this->addMessage('writableMedia', 'danger');
        }

        $ilchdate = new IlchDate;
        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->isPost()) {
            $upload = new \Ilch\Upload();
            $upload->setFile($_FILES['upl']['name']);
            $upload->setTypes($this->getConfig()->get('media_ext_img'));
            $upload->setPath($this->getConfig()->get('media_uploadpath'));
            // Early return if extension is not allowed. Should normally already be done client-side.
            $upload->setAllowedExtensions($allowedExtensions);
            if (!file_exists($_FILES['upl']['tmp_name']) || !$upload->isAllowedExtension()) {
                return;
            }
            $upload->upload();

            $model = new \Modules\Media\Models\Media();
            $model->setUrl($upload->getUrl());
            $model->setUrlThumb($upload->getUrlThumb());
            $model->setEnding($upload->getEnding());
            $model->setName($upload->getName());
            $model->setDatetime($ilchdate->toDb());
            $mediaMapper->save($model);
        }
    }

    public function delAction()
    {
        $mediaMapper = new MediaMapper();
        $mediaMapper->delMediaById($this->getRequest()->getParam('id'));
        $this->addMessage('deleteSuccess');
        $this->redirect(['action' => 'index']);
    }

    public function refreshAction()
    {
        $mediaMapper = new MediaMapper();
        $image = $mediaMapper->getByWhere(['id' => $this->getRequest()->getParam('id')]);

        if ($image !== null) {
            $upload = new \Ilch\Upload();
            $upload->setURL($image->getUrl());
            $upload->setPath($this->getConfig()->get('media_uploadpath'));
            $result = $upload->createThumbnail();

            if (!$result) {
                $this->addMessage('refreshFailure');
            }

            $model = new \Modules\Media\Models\Media();
            $model->setId($image->getId());
            $model->setUrl($image->getUrl());
            $model->setUrlThumb($upload->getUrlThumb());
            $model->setEnding($image->getEnding());
            $model->setName($image->getName());
            $model->setDatetime($image->getDatetime());
            $mediaMapper->save($model);

            $this->addMessage('refreshSuccess');
        } else {
            $this->addMessage('refreshFailure');
        }
        $this->redirect(['action' => 'index']);
    }
}
