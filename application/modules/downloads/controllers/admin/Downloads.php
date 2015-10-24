<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Controllers\Admin;

use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Downloads\Mappers\Downloads as DownloadsMapper;
use Modules\Downloads\Controllers\Admin\Base as BaseController;

class Downloads extends BaseController
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionDownloadsInsertFile',
                'icon' => 'fa fa-plus-circle',
                'url'  => 'javascript:media();'
            )
        );
    }

    public function indexAction() 
    {
        
    }

    public function treatDownloadsAction() 
    {
        $fileMapper = new FileMapper();
        $pagination = new \Ilch\Pagination();
        $downloadsMapper = new DownloadsMapper();
        $id = $this->getRequest()->getParam('id');
        $downloadsTitle = $downloadsMapper->getDownloadsById($id);

        if ($this->getRequest()->getPost('action') == 'delete') {
                foreach($this->getRequest()->getPost('check_downloads') as $fileId) {
                    $fileMapper->deleteById($fileId);
                }
                $this->addMessage('deleteSuccess');
                $this->redirect(array('action' => 'treatdownloads','id' => $id));
        }

        if ($this->getRequest()->getPost()) {
            foreach($this->getRequest()->getPost('check_image') as $fileId ) {
                $catId = $this->getRequest()->getParam('id');
                $model = new \Modules\Downloads\Models\File();
                $model->setFileId($fileId);
                $model->setCat($catId);
                $fileMapper->save($model);
            }
        }

        $pagination->setPage($this->getRequest()->getParam('page'));
        $this->getView()->set('file', $fileMapper->getFileByDownloadsId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('downloadsTitle', $downloadsTitle->getTitle());
    }

    public function treatFileAction() 
    {
        $fileMapper = new FileMapper();
        $id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->getPost()) {
            $fileTitle = $this->getRequest()->getPost('fileTitle');
            $fileDesc = $this->getRequest()->getPost('fileDesc');
            $model = new \Modules\Downloads\Models\File();
            $model->setId($id);
            $model->setFileTitle($fileTitle);
            $model->setFileDesc($fileDesc);
            $fileMapper->saveFileTreat($model);

            $this->addMessage('Success');
        }

        $this->getView()->set('file', $fileMapper->getFileById($id));
    }

    public function delAction()
    {
        if($this->getRequest()->isSecure()) {
            $fileMapper = new FileMapper();
            $id = $this->getRequest()->getParam('id');

            $fileMapper->deleteById($id);

            $this->addMessage('deleteSuccess');
            $this->redirect(array('action' => 'treatdownloads', 'id' => $this->getRequest()->getParam('downloads')));
        }
    }
}
