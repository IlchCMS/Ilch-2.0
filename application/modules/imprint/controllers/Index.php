<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Imprint\Controllers;

use Modules\Imprint\Mappers\Imprint as ImprintMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuImprint'), array('action' => 'index'));

        $imprintMapper = new ImprintMapper();
        $imprint = $imprintMapper->getImprint();
        $this->getView()->set('imprint', $imprint);
        $this->getView()->set('imprintStyle', $this->getConfig()->get('imprint_style'));
    }
}


