<?php
/**
 * @copyright Ilch 2
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

        $this->getLayout()->addMenu(
            'menuStatistic',
            $items
        );
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'siteStatistic' => 'required|numeric|integer|min:0|max:1',
                'ilchVersionStatistic' => 'required|numeric|integer|min:0|max:1',
                'modulesStatistic' => 'required|numeric|integer|min:0|max:1',
                'visitsStatistic' => 'required|numeric|integer|min:0|max:1',
                'browserStatistic' => 'required|numeric|integer|min:0|max:1',
                'osStatistic' => 'required|numeric|integer|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                // offset of 1 because the first element is the token. length of 6 because of 6 settings - last element is the action.
                // sites,ilch version,modules,visits,browser,os
                $visibilitySettings = array_slice($this->getRequest()->getPost(), 1, 6);
                $this->getConfig()->set('statistic_visibleStats', implode(',', $visibilitySettings));

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

        $visibilitySettings = explode(',', $this->getConfig()->get('statistic_visibleStats'));
        $this->getView()->set('siteStatistic', $visibilitySettings[0]);
        $this->getView()->set('ilchVersionStatistic', $visibilitySettings[1]);
        $this->getView()->set('modulesStatistic', $visibilitySettings[2]);
        $this->getView()->set('visitsStatistic', $visibilitySettings[3]);
        $this->getView()->set('browserStatistic', $visibilitySettings[4]);
        $this->getView()->set('osStatistic', $visibilitySettings[5]);
    }
}
