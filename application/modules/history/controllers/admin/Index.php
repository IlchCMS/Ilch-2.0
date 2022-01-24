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
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
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

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuHistorys'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('history', $historyMapper->getHistoryById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuHistorys'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        $post = [
            'date' => '',
            'title' => '',
            'symbol' => '',
            'color' => '',
            'text' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'date' => new \Ilch\Date(trim($this->getRequest()->getPost('date'))),
                'title' => trim($this->getRequest()->getPost('title')),
                'symbol' => trim($this->getRequest()->getPost('symbol')),
                'color' => trim($this->getRequest()->getPost('color')),
                'text' => trim($this->getRequest()->getPost('text'))
            ];

            $validation = Validation::create($post, [
                'date' => 'required',
                'title' => 'required',
                'text' => 'required'
            ]);

            if ($validation->isValid()) {
                $model = new HistoryModel();

                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }

                $model->setDate($post['date']);
                $model->setTitle($post['title']);
                $model->setType($post['symbol']);
                $model->setColor($post['color']);
                $model->setText($post['text']);
                $historyMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('post', $post);
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
