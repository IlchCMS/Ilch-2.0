<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Modules\Media\Mappers\Media as MediaMapper;
use Ilch\Date as IlchDate;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'menuMedia',
            [
                [
                    'name' => 'media',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
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
            ]
        );

        $this->getLayout()->addMenuAction
        (
            [
                'name' => 'menuActionAddNew',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'upload'])
            ]
        );
    }

    public function indexAction() 
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('media'), ['action' => 'index']);

        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_medias') > 0) {
            foreach($this->getRequest()->getPost('check_medias') as $mediaId) {
                $mediaMapper->delMediaById($mediaId);
            }
            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }

        $pagination = new \Ilch\Pagination();
        $pagination->setPage($this->getRequest()->getParam('page'));

        if ($this->getRequest()->getParam('rows')) {
            $pagination->setRowsPerPage($this->getRequest()->getParam('rows'));
            $rows = ['rows' => $this->getRequest()->getParam('rows')];
            $this->getView()->set('rows', $rows);
        } else {
            $rows = [];
            $this->getView()->set('rows', $rows);
        }

        if ($this->getRequest()->getPost('search') === 'search') {
            $pagination->setRowsPerPage($this->getRequest()->getPost('rows'));
            $rows = ['rows' => $this->getRequest()->getPost('rows')];
            $this->getView()->set('rows', $rows);
        }

        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('medias', $mediaMapper->getMediaList($pagination));
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

        if (!is_writable(APPLICATION_PATH.'/../'.$this->getConfig()->get('media_uploadpath'))) {
            $this->addMessage('writableMedia', 'danger');
        }

        $ilchdate = new IlchDate;
        $mediaMapper = new MediaMapper();

        if ($this->getRequest()->isPost()) {
            $upload = new \Ilch\Upload();
            $upload->setFile($_FILES['upl']['name']);
            $upload->setTypes($this->getConfig()->get('media_ext_img'));
            $upload->setPath($this->getConfig()->get('media_uploadpath'));
            // Early return if extension is not allowed or file is too big. Should normally already be done client-side.
            // Doing this client-side is especially important for the "file too big"-case as early returning here is already too late.
            $upload->setAllowedExtensions($allowedExtensions);
            if(!$upload->isAllowedExtension() || filesize($_FILES['upl']['name']) > $upload->returnBytes(ini_get('upload_max_filesize'))) {
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
}
