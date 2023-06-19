<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Checkoutbasic\Controllers\Admin;

use Modules\Checkoutbasic\Mappers\Currency as CurrencyMapper;
use Modules\Checkoutbasic\Models\Currency as CurrencyModel;
use Ilch\Validation;

class Currency extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'settings'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'checkout',
            $items
        );
    }

    public function indexAction()
    {
        $currencyMapper = new CurrencyMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('currencies'), ['action' => 'index']);

        if ($this->getRequest()->isPost() && $this->getRequest()->isSecure()) {
            if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_currencies')) {
                foreach ($this->getRequest()->getPost('check_currencies') as $id) {
                    $currency = $currencyMapper->getCurrencyById($id);
                    if ($currency && $currency->getId() == $this->getConfig()->get('checkoutbasic_currency')) {
                        $this->addMessage('currencyInUse', 'danger');
                        continue;
                    }
                    $currencyMapper->deleteCurrencyById($id);
                }
            }
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('currencies', $currencyMapper->getCurrencies());
    }

    public function treatAction()
    {
        $currencyMapper = new CurrencyMapper();
        $id = $this->getRequest()->getParam('id');

        $currencyModel = new CurrencyModel();
        if ($this->getRequest()->getParam('id')) {
            $currencyModel = $currencyMapper->getCurrencyById($this->getRequest()->getParam('id'));

            if (!$currencyModel) {
                $this->redirect()
                    ->withMessage('notfound', 'danger')
                    ->to(['action' => 'index']);
            }

            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('currencies'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('currencies'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat', 'id' => 'treat']);
        }

        if ($this->getRequest()->isPost() && $this->getRequest()->isSecure()) {
            $_POST['name'] = trim($this->getRequest()->getPost('name'));

            $validation = Validation::create($this->getRequest()->getPost(), [
                'name' => 'required|unique:' . $currencyMapper->tablename . ',name,' . $currencyModel->getId(),
            ]);

            if ($validation->isValid()) {
                $currencyModel->setName($this->getRequest()->getPost('name'));
                $currencyMapper->save($currencyModel);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput($this->getRequest()->getPost())
                    ->withErrors($validation->getErrorBag())
                    ->to(['id' => $currencyModel->getId()]);
            }
        }

        $this->getView()->set('currency', $currencyModel);
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $currencyMapper = new CurrencyMapper();

            $currency = $currencyMapper->getCurrencyById($this->getRequest()->getParam('id'));
            if ($currency && $currency->getId() == $this->getConfig()->get('checkoutbasic_currency')) {
                $this->addMessage('currencyInUse', 'danger');
                $this->redirect(['action' => 'index']);
            }

            $currencyMapper->deleteCurrencyById($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }
        $this->redirect(['action' => 'index']);
    }
}
