<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Models\War as WarModel;
use Modules\War\Models\Games as GamesModel;
use Modules\War\Mappers\Games as GamesMapper;
use Modules\User\Mappers\Group as UserGroupMapper;
use Modules\War\Mappers\Maps as MapsMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa-solid fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'menuActionNewWar',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroups',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuMaps',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'maps', 'action' => 'index'])
            ],
            [
                'name' => 'menuGameIcons',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuWars',
            $items
        );
    }

    public function indexAction()
    {
        $warMapper = new WarMapper();
        $pagination = new \Ilch\Pagination();
        $groupMapper = new GroupMapper();
        $enemyMapper = new EnemyMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_war')) {
            foreach ($this->getRequest()->getPost('check_war') as $warId) {
                $warMapper->delete($warId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('war_warsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_warsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        if ($this->getRequest()->getParam('filterLastNext')) {
            $this->getView()->set('war', $warMapper->getWarListByStatus($this->getRequest()->getParam('filterLastNext'), $pagination));
        } else {
            $this->getView()->set('war', $warMapper->getWarList($pagination, '1'));
        }

        $this->getView()->set('pagination', $pagination)
            ->set('groupMapper', $groupMapper)
            ->set('enemyMapper', $enemyMapper);
    }

    public function treatAction()
    {
        $enemyMapper = new EnemyMapper();
        $groupMapper = new GroupMapper();
        $warMapper = new WarMapper();
        $warModel = new WarModel();
        $gameMapper = new GamesMapper();
        $gameModel = new GamesModel();
        $userGroupMapper = new UserGroupMapper();
        $mapsMapper = new MapsMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageWar'), ['action' => 'treat']);

            $warModel = $warMapper->getWarById($this->getRequest()->getParam('id'));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuActionNewWar'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('warXonx') === 'neu') {
                $_POST['warXonx'] = $this->getRequest()->getPost('warXonxNew');
            }

            if ($this->getRequest()->getPost('warGame') === 'neu') {
                $_POST['warGame'] = $this->getRequest()->getPost('warGameNew');
            }

            if ($this->getRequest()->getPost('warMatchtype') === 'neu') {
                $_POST['warMatchtype'] = $this->getRequest()->getPost('warMatchtypeNew');
            }
            
            $validator = [
                'warXonx'           => 'required',
                'warGame'           => 'required',
                'warMatchtype'      => 'required',
                'warEnemy'          => 'required',
                'warGroup'          => 'required',
                'warTime'           => 'required|date:d.m.Y H\:i',
                'warMap'            => 'required',
                'warServer'         => 'required',
                'lastAcceptTime'    => 'numeric|integer'
            ];
            
            if ($warMapper->existsTable('calendar')) {
                $validator['calendarShow'] = 'required|numeric|min:0|max:1';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validator);

            if ($validation->isValid()) {
                $groups = '';
                if (!empty($this->getRequest()->getPost('groups'))) {
                    if (in_array('all', $this->getRequest()->getPost('groups'))) {
                        $groups = 'all';
                    } else {
                        $groups = implode(',', $this->getRequest()->getPost('groups'));
                    }
                }
                $warMap = '';
                if (!empty($this->getRequest()->getPost('warMap'))) {
                    $warMap = implode(',', $this->getRequest()->getPost('warMap'));
                }

                if ($warModel->getId() && $this->getRequest()->getPost('warMapPlayed')) {
                    $ids = $this->getRequest()->getPost('warGameIds');
                    $maps = $this->getRequest()->getPost('warMapPlayed');
                    $groupPoints = $this->getRequest()->getPost('warErgebnisGroup');
                    $enemyPoints = $this->getRequest()->getPost('warErgebnisEnemy');

                    for ($i = 0; $i < \count($maps); $i++) {
                        if (!empty($ids[$i])) {
                            $gameModel->setId($ids[$i]);
                        }
                        $gameModel->setWarId($warModel->getId())
                            ->setMap($maps[$i])
                            ->setGroupPoints($groupPoints[$i])
                            ->setEnemyPoints($enemyPoints[$i]);
                        $gameMapper->save($gameModel);
                    }
                }

                $warModel->setWarEnemy($this->getRequest()->getPost('warEnemy'))
                    ->setWarGroup($this->getRequest()->getPost('warGroup'))
                    ->setWarTime(new \Ilch\Date($this->getRequest()->getPost('warTime')))
                    ->setWarMaps($warMap)
                    ->setWarServer($this->getRequest()->getPost('warServer'))
                    ->setWarPassword($this->getRequest()->getPost('warPassword'))
                    ->setWarXonx($this->getRequest()->getPost('warXonx'))
                    ->setWarGame($this->getRequest()->getPost('warGame'))
                    ->setWarMatchtype($this->getRequest()->getPost('warMatchtype'))
                    ->setWarReport($this->getRequest()->getPost('warReport'))
                    ->setWarStatus($this->getRequest()->getPost('warStatus'))
                    ->setReadAccess($groups)
                    ->setLastAcceptTime($this->getRequest()->getPost('lastAcceptTime'));

                if ($warMapper->existsTable('calendar')) {
                    $warModel->setShow($this->getRequest()->getPost('calendarShow'));
                }
                $warMapper->save($warModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($warModel->getId()?['id' => $warModel->getId()]:[])));
        }

        if ($warMapper->existsTable('calendar')) {
            $this->getView()->set('calendarShow', 1);
        }

        if ($warModel->getId()) {
            $groups = explode(',', $warModel->getReadAccess());
        } else {
            $groups = [2, 3];
        }

        if ($warModel->getId()) {
            $maps = explode(',', $warModel->getWarMaps());
        } else {
            $maps = [];
        }

        $this->getView()->set('war', $warModel)
            ->set('group', $groupMapper->getGroups())
            ->set('enemy', $enemyMapper->getEnemy())
            ->set('warOptXonx', $warMapper->getWarOptDistinctXonx())
            ->set('warOptGame', $warMapper->getWarOptDistinctGame())
            ->set('warOptMatchtype', $warMapper->getWarOptDistinctMatchtype())
            ->set('userGroupList', $userGroupMapper->getGroupList())
            ->set('groups', $groups)
            ->set('warMap', $maps)
            ->set('mapsList', $mapsMapper->getList());
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $warMapper = new WarMapper();
            $warMapper->delete((int)$this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }
        $this->redirect(['action' => 'index']);
    }

    public function updateAction()
    {
        if ($this->getRequest()->isSecure()) {
            $warMapper = new WarMapper();
            $warMapper->updateShow((int)$this->getRequest()->getParam('id'), $this->getRequest()->getParam('status_man') ?? -1);

            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
