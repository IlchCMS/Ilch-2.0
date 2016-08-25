<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Controllers;

use Modules\Awards\Mappers\Awards as AwardsMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{    
    public function indexAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index']);

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('awards', $awardsMapper->getAwards());
        $this->getView()->set('awardsCount', count($awardsMapper->getAwards()));
    }
}


