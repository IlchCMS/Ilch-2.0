<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Media\Controllers\Admin;

use Media\Mappers\Media as MediaMapper;

use Ilch\Date as IlchDate;

defined('ACCESS') or die('no direct access');

class Iframe extends \Ilch\Controller\Admin 
{
    public function indexAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $MediaMapper = new MediaMapper();
        $this->getView()->set('medias', $MediaMapper->getMediaList());
        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function multiAction()
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $MediaMapper = new MediaMapper();
        $this->getView()->set('medias', $MediaMapper->getMediaList());
        $this->getView()->set('media_ext_img', $this->getConfig()->get('media_ext_img'));
        $this->getView()->set('media_ext_file', $this->getConfig()->get('media_ext_file'));
        $this->getView()->set('media_ext_video', $this->getConfig()->get('media_ext_video'));
    }

    public function uploadAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
    }
}
