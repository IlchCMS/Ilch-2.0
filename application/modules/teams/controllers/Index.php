<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Controllers;

use Modules\Teams\Mappers\Teams as TeamsMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $teamsMapper = new TeamsMapper();
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuTeams'), ['action' => 'index']);

        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('groupMapper', $groupMapper);
        $this->getView()->set('teams', $teamsMapper->getTeams());
    }
}


