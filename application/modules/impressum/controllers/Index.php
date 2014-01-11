<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Impressum\Controllers;

use Impressum\Mappers\Impressum as ImpressumMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuImpressum'), array('action' => 'index'));

        $impressumMapper = new ImpressumMapper();
        $impressum = $impressumMapper->getImpressum();
        $this->getView()->set('impressum', $impressum);
    }
}


