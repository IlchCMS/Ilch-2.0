<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers\Admin;

use Modules\Forum\Mappers\Reports as ReportMapper;
use Ilch\Validation;

class Reports extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'forum',
                'active' => false,
                'icon' => 'fa fa-th',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuRanks',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'ranks', 'action' => 'index'])
            ],
            [
                'name' => 'menuReports',
                'active' => true,
                'icon' => 'fas fa-flag',
                'url' => $this->getLayout()->getUrl(['controller' => 'reports', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'forum',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('reports'), ['action' => 'index']);

        $reportMapper = new ReportMapper();

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_forumReports')) {
            foreach ($this->getRequest()->getPost('check_forumReports') as $reportId) {
                $reportMapper->deleteReport($reportId);
            }
        }

        $this->getView()->set('reports', $reportMapper->getReports());
    }

    public function showAction()
    {
        $id = $this->getRequest()->getParam('id');

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('reports'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('reportDetails'), ['action' => 'show', 'id' => $id]);

        $reportMapper = new ReportMapper();

        $this->getView()->set('report', $reportMapper->getReportById($id));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $reportMapper = new ReportMapper();

            $reportMapper->deleteReport($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}
