<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Controllers\Admin;

use Ilch\Validation;
use Modules\Statistic\Models\Statisticconfig as StatisticConfigModel;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => true,
                'icon' => 'fa-solid fa-table-list',
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
        $statisticConfigModel = new StatisticConfigModel();
        if ($this->getRequest()->isPost()) {
            $validationRules = [];
            foreach ($statisticConfigModel->configNames as $name) {
                $validationRules[$name] = 'required|numeric|integer|min:0|max:1';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $statisticConfigModel->setByArray($this->getRequest()->getPost());
                $this->getConfig()->set('statistic_visibleStats', $statisticConfigModel->getConfigString());

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'index']);
            }
        }

        $statisticConfigModel->setByArray();
        $this->getView()->set('statistic_config', $statisticConfigModel);
    }
}
