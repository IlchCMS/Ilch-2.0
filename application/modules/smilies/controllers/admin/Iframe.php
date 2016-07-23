<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Smilies\Controllers\Admin;

use Modules\Smilies\Mappers\Smilies as SmiliesMapper;

class Iframe extends \Ilch\Controller\Admin
{
    public function smiliesAction() 
    {
        $this->getLayout()->setFile('modules/admin/layouts/iframe');
        $smiliesMapper = new SmiliesMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));
        $lastId = $this->getRequest()->getParam('lastid');
        $type = $this->getConfig()->get('smiley_filetypes');

        if (empty($lastId)) {
            $this->getView()->set('smilies', $smiliesMapper->getSmiliesListByEnding($type, $pagination));
        } else {
            $this->getView()->set('smilies', $smiliesMapper->getSmiliesListScroll($lastId));
        }

        $this->getView()->set('smiley_filetypes', $this->getConfig()->get('smiley_filetypes'));
    }
}
