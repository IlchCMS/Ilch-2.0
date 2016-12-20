<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Mappers\Group as GroupMapper;
use Modules\War\Mappers\War as WarMapper;
use Modules\War\Models\War as WarModel;
use Modules\War\Models\Games as GamesModel;
use Modules\War\Mappers\Games as GamesMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'menuActionNewWar',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroups',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuWars',
            $items
        );
    }

    public function indexAction()
    {
        $warMapper = new WarMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_war')) {
            foreach ($this->getRequest()->getPost('check_war') as $warId) {
                $warMapper->delete($warId);
            }
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('war_warsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_warsPerPage'));
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
                ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageWar'), ['action' => 'treat']);

            $war = $warMapper->getWarById($this->getRequest()->getParam('id'));
            $this->getView()->set('war', $war);
            $this->getView()->set('warOptXonx', $warMapper->getWarOptDistinctXonx());
            $this->getView()->set('warOptGame', $warMapper->getWarOptDistinctGame());
            $this->getView()->set('warOptMatchtype', $warMapper->getWarOptDistinctMatchtype());
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageWarOverview'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuActionNewWar'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getParam('id')) {
                $warModel->setId($this->getRequest()->getParam('id'));
            }

            if ($this->getRequest()->getPost('warXonx') == 'neu') {
                $post['warXonx'] = $this->getRequest()->getPost('warXonxNew');
            } else {
                $post['warXonx'] = $this->getRequest()->getPost('warXonx');
            }

            if ($this->getRequest()->getPost('warGame') == 'neu') {
                $post['warGame'] = $this->getRequest()->getPost('warGameNew');
            } else {
                $post['warGame'] = $this->getRequest()->getPost('warGame');
            }

            if ($this->getRequest()->getPost('warMatchtype') == 'neu') {
                $post['warMatchtype'] = $this->getRequest()->getPost('warMatchtypeNew');
            } else {
                $post['warMatchtype'] = $this->getRequest()->getPost('warMatchtype');
            }

            $post['warEnemy'] = trim($this->getRequest()->getPost('warEnemy'));
            $post['warGroup'] = trim($this->getRequest()->getPost('warGroup'));
            $post['warTime'] = new \Ilch\Date(trim($this->getRequest()->getPost('warTime')));
            $post['warMap'] = trim($this->getRequest()->getPost('warMap'));
            $post['warServer'] = trim($this->getRequest()->getPost('warServer'));
            $post['warPassword'] = $this->getRequest()->getPost('warPassword');
            $post['warReport'] = $this->getRequest()->getPost('warReport');
            $post['warStatus'] = $this->getRequest()->getPost('warStatus');

            $validation = Validation::create($post, [
                'warXonx' => 'required',
                'warGame' => 'required',
                'warMatchtype' => 'required',
                'warEnemy' => 'required',
                'warGroup' => 'required',
                'warTime' => 'required',
                'warMap' => 'required',
                'warServer' => 'required'
            ]);

            if ($validation->isValid()) {
                if ($this->getRequest()->getPost('warMapPlayed')) {
                    $warId = $this->getRequest()->getParam('id');

                    $ids = $this->getRequest()->getPost('warGameIds');
                    $maps = $this->getRequest()->getPost('warMapPlayed');
                    $groupPoints = $this->getRequest()->getPost('warErgebnisGroup');
                    $enemyPoints = $this->getRequest()->getPost('warErgebnisEnemy');

                    for ($i = 0; $i < count($maps); $i++) {
                        $gameModel->setId($ids[$i]);
                        $gameModel->setWarId($warId);
                        $gameModel->setMap($maps[$i]);
                        $gameModel->setGroupPoints($groupPoints[$i]);
                        $gameModel->setEnemyPoints($enemyPoints[$i]);
                        $gameMapper->save($gameModel);
                    }
                }

                $warModel->setWarEnemy($post['warEnemy']);
                $warModel->setWarGroup($post['warGroup']);
                $warModel->setWarTime($post['warTime']);
                $warModel->setWarMaps($post['warMap']);
                $warModel->setWarServer($post['warServer']);
                $warModel->setWarPassword($post['warPassword']);
                $warModel->setWarXonx($post['warXonx']);
                $warModel->setWarGame($post['warGame']);
                $warModel->setWarMatchtype($post['warMatchtype']);
                $warModel->setWarReport($post['warReport']);
                $warModel->setWarStatus($post['warStatus']);
                $warMapper->save($warModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->redirect()
                ->withInput($post)
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
        }

        $this->getView()->set('group', $groupMapper->getGroups());
        $this->getView()->set('enemy', $enemyMapper->getEnemy());
        $this->getView()->set('warOptXonx', $warMapper->getWarOptDistinctXonx());
        $this->getView()->set('warOptGame', $warMapper->getWarOptDistinctGame());
        $this->getView()->set('warOptMatchtype', $warMapper->getWarOptDistinctMatchtype());
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $id = (int)$this->getRequest()->getParam('id');
            $warMapper = new WarMapper();
            $warMapper->delete($id);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
