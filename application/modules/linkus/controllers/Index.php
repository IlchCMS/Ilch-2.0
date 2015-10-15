<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Linkus\Controllers;

use Modules\Linkus\Mappers\Linkus as LinkusMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $linkusMapper = new LinkusMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuLinkus'), array('action' => 'index'));

        $this->getView()->set('linkus', $linkusMapper->getLinkus());
    }
}
