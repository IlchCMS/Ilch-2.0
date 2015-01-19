<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Media\Controllers\Admin;

use Modules\Media\Mappers\Media as MediaMapper;

defined('ACCESS') or die('no direct access');

class Iframe extends \Ilch\Controller\Admin 
{
    public function indexAction() 
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

    public function multiAction()
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
}
