<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Forum\Mappers\Rank as RankMapper;
use Modules\Forum\Models\Rank as RankModel;
use Ilch\Validation;

class Ranks extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'forum',
                'active' => false,
                'icon' => 'fa-solid fa-table-cells',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuRanks',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuPrefixes',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'prefixes', 'action' => 'index'])
            ],
            [
                'name' => 'menuReports',
                'active' => false,
                'icon' => 'fa-solid fa-flag',
                'url' => $this->getLayout()->getUrl(['controller' => 'reports', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'forum',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('ranks'), ['action' => 'index']);

        $rankMapper = new RankMapper();

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_forumRanks')) {
            foreach ($this->getRequest()->getPost('check_forumRanks') as $rankId) {
                $rankMapper->delete($rankId);
            }
        }

        $this->getView()->set('ranks', $rankMapper->getRanks());
    }

    public function treatAction()
    {
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($this->getTranslator()->trans('ranks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($this->getTranslator()->trans('ranks'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $rankModel = new RankModel();
        $rankMapper = new RankMapper();

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'title' => 'required',
                'posts' => 'required|numeric|integer|min:0|max:2147483647'
            ]);

            if ($validation->isValid()) {
                $rankModel->setId($this->getRequest()->getParam('id'));
                $rankModel->setTitle($this->getRequest()->getPost('title'));
                $rankModel->setPosts($this->getRequest()->getPost('posts'));
                $rankMapper->save($rankModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
        }

        $this->getView()->set('rank', ($this->getRequest()->getParam('id')) ? $rankMapper->getRankById($this->getRequest()->getParam('id')) : null);
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $rankMapper = new RankMapper();
            $rankMapper->delete($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}
