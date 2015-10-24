<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Controllers;

use Modules\Awards\Mappers\Awards as AwardsMapper;

class Index extends \Ilch\Controller\Frontend
{    
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuAwards'), array('action' => 'index'));
        $awardsMapper = new AwardsMapper();

        $this->getView()->set('awards', $awardsMapper->getAwards());
        $this->getView()->set('awardsCount', count($awardsMapper->getAwards()));
    }
}


