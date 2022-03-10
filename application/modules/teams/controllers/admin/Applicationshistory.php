<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Teams\Controllers\Admin;

use Modules\Teams\Mappers\Joins as JoinsMapper;
use Modules\Teams\Mappers\Teams as TeamsMapper;

class Applicationshistory extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'applications',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'applications', 'action' => 'index']),
                [
                    'name' => 'history',
                    'active' => true,
                    'icon' => 'fa fa-folder-open',
                    'url' => $this->getLayout()->getUrl(['controller' => 'applicationshistory', 'action' => 'index'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuTeams',
            $items
        );
    }

    public function indexAction()
    {
        $joinsMapper = new JoinsMapper();
        $teamsMapper = new TeamsMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuApplicationsHistory'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $joinsMapper->clearHistory();
        }

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('teamsMapper', $teamsMapper)
            ->set('joins', $joinsMapper->getApplicationHistory($pagination))
            ->set('pagination', $pagination);
    }

    public function showAction()
    {
        $joinsMapper = new JoinsMapper();
        $teamsMapper = new TeamsMapper();

        $join = $joinsMapper->getJoinInHistoryById($this->getRequest()->getParam('id'));

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuApplicationsHistory'), ['controller' => 'applications', 'action' => 'index'])
            ->add($join->getName(), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        $this->getView()->set('joinsMapper', $joinsMapper)
            ->set('teamsMapper', $teamsMapper)
            ->set('join', $join);
    }
    
    public function showuserhistoryAction()
    {
        $joinsMapper = new JoinsMapper();
        $teamsMapper = new TeamsMapper();
        $pagination = new \Ilch\Pagination();

        $pagination->setRowsPerPage($this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $joins = $joinsMapper->getApplicationHistoryByUserId($this->getRequest()->getParam('userId'), $pagination);

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuTeams'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuApplicationsHistory'), ['controller' => 'applications', 'action' => 'index'])
            ->add($joins[0]->getName(), ['action' => 'showuserhistory', 'userId' => $this->getRequest()->getParam('userId')]);

        $this->getView()->set('joinsMapper', $joinsMapper)
            ->set('teamsMapper', $teamsMapper)
            ->set('joins', $joins)
            ->set('pagination', $pagination);
    }
}
