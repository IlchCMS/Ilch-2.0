<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Controllers\Admin;

use Modules\War\Mappers\Enemy as EnemyMapper;
use Modules\War\Models\Enemy as EnemyModel;
use Ilch\Validation;

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
                'name' => 'menuMaps',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'maps', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuGameIcon',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'icons', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
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

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_enemy')) {
            foreach ($this->getRequest()->getPost('check_enemy') as $enemyId) {
                $enemyMapper->delete($enemyId);
            }
            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('war_enemiesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('war_enemiesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('enemy', $enemyMapper->getEnemyList($pagination))
            ->set('pagination', $pagination);
    }

    public function treatAction()
    {
        $enemyMapper = new EnemyMapper();
        $enemyModel = new EnemyModel();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageEnemy'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatEnemy'), ['action' => 'treat']);

            $enemyModel = $enemyMapper->getEnemyById($this->getRequest()->getParam('id'));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('manageEnemy'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manageNewEnemy'), ['action' => 'treat']);
        }

        $post = [
            'enemyName' => '',
            'enemyTag' => '',
            'enemyImage' => '',
            'enemyHomepage' => '',
            'enemyContactName' => '',
            'enemyContactEmail' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $enemyImage = $this->getRequest()->getPost('enemyImage');
            if (!empty($enemyImage)) {
                $enemyImage = BASE_URL . '/' . $enemyImage;
            }

            $post = [
                'enemyName' => $this->getRequest()->getPost('enemyName'),
                'enemyTag' => $this->getRequest()->getPost('enemyTag'),
                'enemyImage' => $enemyImage,
                'enemyHomepage' => $this->getRequest()->getPost('enemyHomepage'),
                'enemyContactName' => $this->getRequest()->getPost('enemyContactName'),
                'enemyContactEmail' => $this->getRequest()->getPost('enemyContactEmail')
            ];
            
            $validator = [
                'enemyName' => 'required|unique:war_enemy,name',
                'enemyTag' => 'required|unique:war_enemy,tag',
                'enemyHomepage' => 'url',
                'enemyImage' => 'url',
                'enemyContactEmail' => 'email'
            ];
            
            if ($enemyModel->getId()) {
                $validator['enemyName'] = 'required';
                $validator['enemyTag'] = 'required';
            }

            $validation = Validation::create($post, $validator);

            if ($validation->isValid()) {
                $enemyModel->setEnemyName($this->getRequest()->getPost('enemyName'))
                    ->setEnemyTag($this->getRequest()->getPost('enemyTag'))
                    ->setEnemyImage($this->getRequest()->getPost('enemyImage'))
                    ->setEnemyHomepage($this->getRequest()->getPost('enemyHomepage'))
                    ->setEnemyContactName($this->getRequest()->getPost('enemyContactName'))
                    ->setEnemyContactEmail($this->getRequest()->getPost('enemyContactEmail'));
                $enemyMapper->save($enemyModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($enemyModel->getId()?['id' => $enemyModel->getId()]:[])));
        }


        $this->getView()->set('enemy', $enemyModel);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $enemyMapper = new EnemyMapper();
            $enemyMapper->delete((int)$this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
