<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers;

use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Mappers\Games as GameMapper;
use Modules\War\Mappers\War as WarMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuWarList'), array('action' => 'index'));

        $pagination = new \Ilch\Pagination();
        $warMapper = new WarMapper();

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('war', $warMapper->getWarList($pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showAction()
    {
        $warMapper = new WarMapper();
        $gameMapper = new GameMapper();
        $groupMapper = new GroupMapper();
        $enemyMapper = new EnemyMapper();

        $war = $warMapper->getWarById($this->getRequest()->getParam('id'));
        $this->getView()->set('games', $gameMapper->getGamesByWarId($this->getRequest()->getParam('id')));
        $group = $groupMapper->getGroupById($war->getWarGroup());
        $enemy = $enemyMapper->getEnemyById($war->getWarEnemy());

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuWarList'), array('action' => 'index'))
            ->add($group->getGroupName(), array('controller' => 'group', 'action' => 'show', 'id' => $group->getId()))
            ->add($this->getTranslator()->trans('warPlay'), array('action' => 'show', 'id' => $this->getRequest()->getParam('id')));

        $this->getView()->set('group', $group);
        $this->getView()->set('enemy', $enemy);
        $this->getView()->set('war', $war);
    }
}
