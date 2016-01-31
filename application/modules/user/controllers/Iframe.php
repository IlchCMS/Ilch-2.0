<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\Media as MediaMapper;

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
    }
}
