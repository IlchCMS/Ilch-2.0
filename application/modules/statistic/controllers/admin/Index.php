<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Statistic\Controllers\Admin;

use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuStatistic',
            $items
        );
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'siteStatistic' => 'required|numeric|integer|min:0|max:1',
                'visitsStatistic' => 'required|numeric|integer|min:0|max:1',
                'browserStatistic' => 'required|numeric|integer|min:0|max:1',
                'osStatistic' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('statistic_site', $this->getRequest()->getPost('siteStatistic'));
                $this->getConfig()->set('statistic_visits', $this->getRequest()->getPost('visitsStatistic'));
                $this->getConfig()->set('statistic_browser', $this->getRequest()->getPost('browserStatistic'));
                $this->getConfig()->set('statistic_os', $this->getRequest()->getPost('osStatistic'));

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'index']);
            }
        }

        $this->getView()->set('siteStatistic', $this->getConfig()->get('statistic_site'));
        $this->getView()->set('visitsStatistic', $this->getConfig()->get('statistic_visits'));
        $this->getView()->set('browserStatistic', $this->getConfig()->get('statistic_browser'));
        $this->getView()->set('osStatistic', $this->getConfig()->get('statistic_os'));
    }
}
