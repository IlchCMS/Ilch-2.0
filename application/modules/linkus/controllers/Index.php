<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Linkus\Controllers;

use Modules\Linkus\Mappers\Linkus as LinkusMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $linkusMapper = new LinkusMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuLinkus'), ['action' => 'index']);

        $this->getView()->set('linkus', $linkusMapper->getLinkus());
    }
}
