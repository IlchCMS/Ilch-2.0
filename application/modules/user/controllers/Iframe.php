<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\Media as MediaMapper;
use Ilch\Date as IlchDate;
use Modules\User\Models\Media as MediaModel;

class Iframe extends \Ilch\Controller\Frontend
{
    public function multiAction()
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $mediaMapper = new MediaMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));
        $lastId = $this->getRequest()->getParam('lastid');

        if ($this->getRequest()->getParam('type') === 'multi') {
            $type = $this->getConfig()->get('usergallery_filetypes');
        }

        if (empty($lastId)) {
            $pagination->setRowsPerPage('40');

            $this->getView()->set('medias', $mediaMapper->getMediaListByEnding($this->getUser()->getId(), $type, $pagination));
        } else {
            $this->getView()->set('medias', $mediaMapper->getMediaListScroll($lastId));
        }

        $this->getView()->set('usergallery_filetypes', $this->getConfig()->get('usergallery_filetypes'));
    }

    public function uploadAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');

        $ilchdate = new IlchDate;
        $mediaMapper = new MediaMapper();


        $allowedExtensions = $this->getConfig()->get('media_ext_img');
        $this->getView()->set('allowedExtensions', $allowedExtensions);

        if (!is_writable(APPLICATION_PATH.'/../'.$this->getConfig()->get('usergallery_uploadpath'))) {
            $this->addMessage('writableMedia', 'danger');
        }

        if ($this->getRequest()->isPost()) {
            if (!is_dir(APPLICATION_PATH.'/../'.$this->getConfig()->get('usergallery_uploadpath').$this->getUser()->getId())) {
                mkdir(APPLICATION_PATH.'/../'.$this->getConfig()->get('usergallery_uploadpath').$this->getUser()->getId(), 0777);
            }

            $upload = new \Ilch\Upload();
            $upload->setFile($_FILES['upl']['name']);
            $upload->setTypes($this->getConfig()->get('usergallery_filetypes'));
            $upload->setPath($this->getConfig()->get('usergallery_uploadpath').$this->getUser()->getId().'/');
            // Early return if extension is not allowed or file is too big. Should normally already be done client-side.
            // Doing this client-side is especially important for the "file too big"-case as early returning here is already too late.
            $upload->setAllowedExtensions($allowedExtensions);
            if(!$upload->isAllowedExtension() || filesize($_FILES['upl']['tmp_name']) > $upload->returnBytes(ini_get('upload_max_filesize'))) {
                return;
            }
            $upload->upload();

            $model = new MediaModel();
            $model->setUserId($this->getUser()->getId());
            $model->setUrl($upload->getUrl());
            $model->setUrlThumb($upload->getUrlThumb());
            $model->setEnding($upload->getEnding());
            $model->setName($upload->getName());
            $model->setDatetime($ilchdate->toDb());
            $mediaMapper->save($model);
        }

    }
}
