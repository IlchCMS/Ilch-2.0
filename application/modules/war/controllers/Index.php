<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers;

defined('ACCESS') or die('no direct access');

use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\War as WarMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuWarList'), array('action' => 'index'));
        
        $groupMapper = new GroupMapper();
        $pagination = new \Ilch\Pagination();
        $warMapper = new WarMapper();
        

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('war', $warMapper->getWarList($pagination));
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('groups', $groupMapper->getGroups());
    }
}
