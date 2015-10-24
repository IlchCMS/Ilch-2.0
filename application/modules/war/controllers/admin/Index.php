<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Controllers\Admin\Base as BaseController;
use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Models\War as WarModel;
use Modules\War\Models\Games as GamesModel;
use Modules\War\Mappers\Games as GamesMapper;

class Index extends BaseController
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuActionNewWar',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }

    public function indexAction()
    {
        $warMapper = new WarMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageWarOverview'), array('action' => 'index'));

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_war')) {
            foreach($this->getRequest()->getPost('check_war') as $warId) {
                $warMapper->delete($warId);
            }
        }

        $pagination->setPage($this->getRequest()->getParam('page'));

        if ($this->getRequest()->getPost('filter') == 'filter' and $this->getRequest()->getPost('filterLastNext') !='0') {
                $status = $this->getRequest()->getPost('filterLastNext');
                $this->getView()->set('war', $warMapper->getWarListByStatus($status, $pagination));
        } else {
            $this->getView()->set('war', $warMapper->getWarList($pagination));
        }

        $this->getView()->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $enemyMapper = new EnemyMapper();
        $groupMapper = new GroupMapper();
        $warMapper = new WarMapper();
        $warModel = new WarModel();
        $gameMapper = new GamesMapper();
        $gameModel = new GamesModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageWarOverview'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('manageWar'), array('action' => 'treat'));

            $war = $warMapper->getWarById($this->getRequest()->getParam('id'));
            $this->getView()->set('war', $war);
            $this->getView()->set('warOptXonx', $warMapper->getWarOptDistinctXonx());
            $this->getView()->set('warOptGame', $warMapper->getWarOptDistinctGame());
            $this->getView()->set('warOptMatchtype', $warMapper->getWarOptDistinctMatchtype());
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageWarOverview'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('menuActionNewWar'), array('action' => 'treat'));
        }

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('warMapPlayed')) {
                $warId = $this->getRequest()->getParam('id');
                $maps = $this->getRequest()->getPost('warMapPlayed');
                $groupPoints = $this->getRequest()->getPost('warErgebnisGroup');
                $enemyPoints = $this->getRequest()->getPost('warErgebnisEnemy');

                $errorMaps = array_keys($groupPoints, true);
                if (empty($errorMaps)) {
                    $this->addMessage('missingWarMapPlayed', 'danger');
                } elseif(empty($groupPoints)) {
                    $this->addMessage('missingGroupPoints', 'danger');
                } elseif(empty($enemyPoints)) {
                    $this->addMessage('missingEnemyPoints', 'danger');
                } else {
                    for ($i = 0; $i < count($maps); $i++) {
                        $gameModel->setWarId($warId);
                        $gameModel->setMap($maps[$i]);
                        $gameModel->setGroupPoints($groupPoints[$i]);
                        $gameModel->setEnemyPoints($enemyPoints[$i]);
                        $gameMapper->save($gameModel);
                    }$this->getView()->set('error', $errorMaps);
                }
            }

            if ($this->getRequest()->getParam('id')) {
                $warModel->setId($this->getRequest()->getParam('id'));
            }

            if ($this->getRequest()->getPost('warXonx') == 'neu') {
                $warXonx = $this->getRequest()->getPost('warXonxNew');
            } else {
                $warXonx = $this->getRequest()->getPost('warXonx');
            }

            if ($this->getRequest()->getPost('warGame') == 'neu') {
                $warGame = $this->getRequest()->getPost('warGameNew');
            } else {
                $warGame = $this->getRequest()->getPost('warGame');
            }

            if ($this->getRequest()->getPost('warMatchtype') == 'neu') {
                $warMatchtype = $this->getRequest()->getPost('warMatchtypeNew');
            } else {
                $warMatchtype = $this->getRequest()->getPost('warMatchtype');
            }

            $enemyName = trim($this->getRequest()->getPost('warEnemy'));
            $groupName = trim($this->getRequest()->getPost('warGroup'));
            $warTime = new \Ilch\Date(trim($this->getRequest()->getPost('warTime')));
            $warMap = trim($this->getRequest()->getPost('warMap'));
            $warServer = trim($this->getRequest()->getPost('warServer'));
            $warPassword = $this->getRequest()->getPost('warPassword');
            $warReport = $this->getRequest()->getPost('warReport');
            $warStatus = $this->getRequest()->getPost('warStatus');

            if (empty($enemyName)) {
                $this->addMessage('missingEnemyName', 'danger');
            } elseif(empty($groupName)) {
                $this->addMessage('missingGroupName', 'danger');
            } elseif(empty($warTime)) {
                $this->addMessage('missingWarTime', 'danger');
            } elseif(empty($warServer)) {
                $this->addMessage('missingWarServer', 'danger');
            } elseif(empty($warXonx)) {
                $this->addMessage('missingWarXonx', 'danger');
            } elseif(empty($warGame)) {
                $this->addMessage('missingWarGame', 'danger');
            } elseif(empty($warMatchtype)) {
                $this->addMessage('missingWarMatchtype', 'danger');
            } else {
                $warModel->setWarEnemy($enemyName);
                $warModel->setWarGroup($groupName);
                $warModel->setWarTime($warTime);
                $warModel->setWarMaps($warMap);
                $warModel->setWarServer($warServer);
                $warModel->setWarPassword($warPassword);
                $warModel->setWarXonx($warXonx);
                $warModel->setWarGame($warGame);
                $warModel->setWarMatchtype($warMatchtype);
                $warModel->setWarReport($warReport);
                $warModel->setWarStatus($warStatus);
                $warMapper->save($warModel);

                $this->addMessage('saveSuccess');

                $this->redirect(array('action' => 'index'));
            }
        }

        $this->getView()->set('group', $groupMapper->getGroups());
        $this->getView()->set('enemy', $enemyMapper->getEnemy());
        $this->getView()->set('warOptXonx', $warMapper->getWarOptDistinctXonx());
        $this->getView()->set('warOptGame', $warMapper->getWarOptDistinctGame());
        $this->getView()->set('warOptMatchtype', $warMapper->getWarOptDistinctMatchtype());
    }

    public function delAction()
    {
        if($this->getRequest()->isSecure()) {
            $id = (int)$this->getRequest()->getParam('id');
            $warMapper = new WarMapper();
            $warMapper->delete($id);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
}
