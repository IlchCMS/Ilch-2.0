<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\History\Controllers\Admin;

use Modules\History\Mappers\History as HistoryMapper;
use Modules\History\Models\History as HistoryModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuHistorys',
            $items
        );
    }

    public function indexAction()
    {
        $historyMapper = new HistoryMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuHistorys'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_entries') as $historyId) {
                $historyMapper->delete($historyId);
            }
        }

        $this->getView()->set('entries', $historyMapper->getEntries());
    }

    public function treatAction()
    {
        $historyMapper = new HistoryMapper();

        $model = new HistoryModel();
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuHistorys'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
            $model = $historyMapper->getHistoryById($this->getRequest()->getParam('id'));

            if (!$model) {
                $this->redirect(['action' => 'index']);
            }
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuHistorys'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('history', $model);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'date' => 'required|date:d.m.Y',
                'title' => 'required',
                'text' => 'required',
                'color' => 'required'
            ]);

            if ($validation->isValid()) {
                $model->setDate(new \Ilch\Date(trim($this->getRequest()->getPost('date'))));
                $model->setTitle($this->getRequest()->getPost('title'));
                $model->setType($this->getRequest()->getPost('symbol'));
                $model->setColor($this->getRequest()->getPost('color'));
                $model->setText($this->getRequest()->getPost('text'));
                $historyMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($model->getId() ? ['id' => $model->getId()] : [])));
        }
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $historyMapper = new HistoryMapper();
            $historyMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
