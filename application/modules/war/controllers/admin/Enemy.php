<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Models\Enemy as EnemyModel;

class Enemy extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuWars',
                'active' => false,
                'icon' => 'fa fa-shield',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuEnemy',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'index']),
                [
                    'name' => 'menuActionNewEnemy',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'enemy', 'action' => 'treat'])
                ]
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
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuWars',
            $items
        );
    }

    public function indexAction()
    {
        $enemyMapper = new EnemyMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageEnemy'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_enemy')) {
            foreach ($this->getRequest()->getPost('check_enemy') as $enemyId) {
                $enemyMapper->delete($enemyId);
            }
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('war_enemiesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_enemiesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('enemy', $enemyMapper->getEnemyList($pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $enemyMapper = new EnemyMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageEnemy'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatEnemy'), ['action' => 'treat']);
            $enemy = $enemyMapper->getEnemyById($this->getRequest()->getParam('id'));
            $this->getView()->set('enemy', $enemy);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageEnemy'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageNewEnemy'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $enemyModel = new EnemyModel();

            if ($this->getRequest()->getParam('id')) {
                $enemyModel->setId($this->getRequest()->getParam('id'));
            }

            $enemyName = trim($this->getRequest()->getPost('enemyName'));
            $enemyTag = trim($this->getRequest()->getPost('enemyTag'));
            $enemyImage = trim($this->getRequest()->getPost('enemyImage'));
            $enemyHomepage = $this->getRequest()->getPost('enemyHomepage');
            $enemyContactName = $this->getRequest()->getPost('enemyContactName');
            $enemyContactEmail = $this->getRequest()->getPost('enemyContactEmail');

            if (empty($enemyName)) {
                $this->addMessage('missingEnemyName', 'danger');
            } elseif (empty($enemyTag)) {
                $this->addMessage('missingEnemyTag', 'danger');
            } elseif (empty($enemyImage)) {
                $this->addMessage('missingEnemyImage', 'danger');
            } elseif (empty($enemyHomepage)) {
                $this->addMessage('missingEnemyHomepage', 'danger');
            } elseif (empty($enemyContactName)) {
                $this->addMessage('missingContactName', 'danger');
            } elseif (empty($enemyContactEmail)) {
                $this->addMessage('missingContactEmail', 'danger');
            } else {
                $enemyModel->setEnemyName($enemyName);
                $enemyModel->setEnemyTag($enemyTag);
                $enemyModel->setEnemyImage($enemyImage);
                $enemyModel->setEnemyHomepage($enemyHomepage);
                $enemyModel->setEnemyContactName($enemyContactName);
                $enemyModel->setEnemyContactEmail($enemyContactEmail);
                $enemyMapper->save($enemyModel);

                $this->addMessage('saveSuccess');

                $this->redirect(['action' => 'index']);
            }
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $id = (int)$this->getRequest()->getParam('id');
            $enemyMapper = new EnemyMapper();
            $enemyMapper->delete($id);

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
