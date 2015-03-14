<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Modules\Media\Mappers\Media as MediaMapper;

defined('ACCESS') or die('no direct access');

class Ajax extends \Ilch\Controller\Admin 
{
    public function indexAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/ajax');
        $mediaMapper = new MediaMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));

        $pageid = $this->getRequest()->getParam('pageid');
        $this->getView()->set('pagination', $pagination);
        if ($pageid == ''){
            $this->getView()->set('medias', $mediaMapper->getMediaList($pagination));
        } else {
            $this->getView()->set('medias', $mediaMapper->getMediaListScroll($pageid));
        }

        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function multiAction()
    {
        $this->getLayout()->setFile('modules/admin/layouts/ajax');
        $mediaMapper = new MediaMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setPage($this->getRequest()->getParam('page'));

        $pageid = $this->getRequest()->getParam('pageid');
        $this->getView()->set('pagination', $pagination);
        if ($pageid == ''){
            $this->getView()->set('medias', $mediaMapper->getMediaList($pagination));
        } else {
            $this->getView()->set('medias', $mediaMapper->getMediaListScroll($pageid));
        }

        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function uploadAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
    }
}
