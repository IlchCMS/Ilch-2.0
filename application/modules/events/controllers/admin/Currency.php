<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers\Admin;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\Events\Mappers\Currency as CurrencyMapper;
use Modules\Events\Models\Currency as CurrencyModel;
use Ilch\Validation;

class Currency extends \Ilch\Controller\Admin
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
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
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
            'menuEvents',
            $items
        );
    }

    public function indexAction()
    {
        $eventMapper = new EventMapper();
        $currencyMapper = new CurrencyMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('currencies'), ['action' => 'index']);

        if ($this->getRequest()->isPost() && $this->getRequest()->isSecure()) {
            if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_currencies')) {
                foreach ($this->getRequest()->getPost('check_currencies') as $id) {
                    if ($eventMapper->getEntries(['currency' => $this->getRequest()->getParam('id')])) {
                        $this->addMessage('currencyInUse', 'danger');
                        continue;
                    }
                    $currencyMapper->deleteCurrencyById($id);
                }
            }
        }

        $this->getView()->set('currencies', $currencyMapper->getCurrencies());
    }

    public function treatAction()
    {
        $currencyMapper = new CurrencyMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuEvents'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('currencies'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuEvents'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('currencies'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat', 'id' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'name' => 'required|unique:events_currencies,name'
            ]);

            if ($validation->isValid()) {
                $currencyModel = new CurrencyModel();
                if ($this->getRequest()->getParam('id')) {
                    $currencyModel->setId($this->getRequest()->getParam('id'));
                }
                $currencyModel->setName($this->getRequest()->getPost('name'));
                $currencyMapper->save($currencyModel);

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

        $currency = $currencyMapper->getCurrencyById($this->getRequest()->getParam('id'));
        if (count($currency) > 0) {
            $currency = $currency[0];
        } else {
            $currency = new CurrencyModel();
        }

        $this->getView()->set('currency', $currency);
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $eventMapper = new EventMapper();
            $currencyMapper = new CurrencyMapper();

            if ($eventMapper->getEntries(['currency' => $this->getRequest()->getParam('id')])) {
                $this->redirect()
                    ->withMessage('currencyInUse', 'danger')
                    ->to(['action' => 'index']);
            }

            $currencyMapper->deleteCurrencyById($this->getRequest()->getParam('id'));

            $this->redirect()
                ->withMessage('deleteSuccess')
                ->to(['action' => 'index']);
        }
    }
}
