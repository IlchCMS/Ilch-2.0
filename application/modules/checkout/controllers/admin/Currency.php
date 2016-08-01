<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkout\Controllers\Admin;

use Modules\Checkout\Mappers\Currency as CurrencyMapper;
use Modules\Checkout\Models\Currency as CurrencyModel;

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
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'settings'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
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
                    if ($currencyMapper->getCurrencyById($id)[0]->getId() == $this->getConfig()->get('checkout_currency')) {
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
        $id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->getParam('id')) {
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
            $id = $this->getRequest()->getPost('id');
            $name = trim($this->getRequest()->getPost('name'));

            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
            } elseif ($currencyMapper->currencyWithNameExists($name)) {
                $this->addMessage('alreadyExisting', 'danger');
            } else {
                $currencyModel = new CurrencyModel();

                $currencyModel->setId($id);
                $currencyModel->setName($name);
                $currencyMapper->save($currencyModel);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }
        }

        $currency = $currencyMapper->getCurrencyById($id);
        if (count($currency) > 0) {
            $currency = $currency[0];
        } else {
            $currency = new CurrencyModel();
        }

        $this->getView()->set('currency', $currency);
    }

    public function deleteAction()
    {
        if ($this->getRequest() && $this->getRequest()->isSecure()) {
            $currencyMapper = new CurrencyMapper();

            $id = $this->getRequest()->getParam('id');
            if ($currencyMapper->getCurrencyById($id)[0]->getId() == $this->getConfig()->get('checkout_currency')) {
                $this->addMessage('currencyInUse', 'danger');
                $this->redirect(['action' => 'index']);
            }

            $currencyMapper->deleteCurrencyById($id);

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }
    }
}