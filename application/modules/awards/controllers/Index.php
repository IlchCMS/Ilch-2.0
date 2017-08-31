<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Awards\Controllers;

use Modules\Awards\Mappers\Awards as AwardsMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Teams\Mappers\Teams as TeamsMapper;

class Index extends \Ilch\Controller\Frontend
{    
    public function indexAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();
        $teamsMapper = new TeamsMapper();

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index']);

        $this->getView()->set('userMapper', $userMapper)
            ->set('teamsMapper', $teamsMapper)
            ->set('awards', $awardsMapper->getAwards())
            ->set('awardsCount', count($awardsMapper->getAwards()));
    }
}


