<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Modules\Media\Mappers\Media as MediaMapper;

class Iframe extends \Ilch\Controller\Admin
{
    public function indexAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $mediaMapper = new MediaMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));
        $lastId = $this->getRequest()->getParam('lastid');

        if ($this->getRequest()->getParam('type') === 'multi' || $this->getRequest()->getParam('type') === 'single') {
            $type = $this->getConfig()->get('media_ext_img');
        }

        if ($this->getRequest()->getParam('type') === 'file') {
            $type = $this->getConfig()->get('media_ext_file');
        }

        if ($this->getRequest()->getParam('type') === 'video') {
            $type = $this->getConfig()->get('media_ext_video');
        }

        if (empty($lastId)) {
            $pagination->setRowsPerPage('40');
            $this->getView()->set('medias', $mediaMapper->getMediaListByEnding($type, $pagination));
        } else {
            $this->getView()->set('medias', $mediaMapper->getMediaListScroll($lastId));
        }

        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function indexckeditorAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $mediaMapper = new MediaMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));

        $lastId = $this->getRequest()->getParam('lastid');

        if ($this->getRequest()->getParam('type') === 'multi' || $this->getRequest()->getParam('type') === 'single' || $this->getRequest()->getParam('type') == 'imageckeditor') {
            $type = $this->getConfig()->get('media_ext_img');
        }

        if ($this->getRequest()->getParam('type') === 'file' || $this->getRequest()->getParam('type') === 'fileckeditor') {
            $type = $this->getConfig()->get('media_ext_file');
        }

        if ($this->getRequest()->getParam('type') === 'video' || $this->getRequest()->getParam('type') === 'videockeditor') {
            $type = $this->getConfig()->get('media_ext_video');
        }

        if (empty($lastId)) {
            $pagination->setRowsPerPage('40');
            $this->getView()->set('medias', $mediaMapper->getMediaListByEnding($type, $pagination));
        } else {
            $this->getView()->set('medias', $mediaMapper->getMediaListScroll($lastId));
        }

        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function multiAction()
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $mediaMapper = new MediaMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));
        $lastId = $this->getRequest()->getParam('lastid');

        if ($this->getRequest()->getParam('type') === 'multi') {
            $type = $this->getConfig()->get('media_ext_img');
        }

        if ($this->getRequest()->getParam('type') === 'file') {
            $type = $this->getConfig()->get('media_ext_file');
        }

        if ($this->getRequest()->getParam('type') === 'video') {
            $type = $this->getConfig()->get('media_ext_video');
        }

        if (empty($lastId)) {
            $pagination->setRowsPerPage('40');
            $this->getView()->set('medias', $mediaMapper->getMediaListByEnding($type, $pagination));
        } else {
            $this->getView()->set('medias', $mediaMapper->getMediaListScroll($lastId));
        }

        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function multickeditorAction()
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $mediaMapper = new MediaMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('medias', $mediaMapper->getMediaList($pagination));
        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function uploadAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
    }

    public function uploadckeditorAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
    }
}
