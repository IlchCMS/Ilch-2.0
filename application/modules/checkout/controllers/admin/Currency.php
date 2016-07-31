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
                'name' => 'accountdata',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'settings'])
            ],
            [
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'addCurrency',
                'active' => false,
                'icon' => 'fa fa-plus-circle',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'treat', 'id' => 0])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'settings') {
            $items[1]['active'] = true;
        } elseif ($this->getRequest()->getControllerName() == 'currency') {
            $items[2]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'checkout',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('currency'), ['action' => 'index']);

        $currencyMapper = new CurrencyMapper();

        if ($this->getRequest()->isPost()) {

        }

        $this->getView()->set('currencies', $currencyMapper->getCurrencies());
    }

    public function treatAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('currency'), ['action' => 'index']);

        $currencyMapper = new CurrencyMapper();

        $id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getPost('id');
            $name = $this->getRequest()->getPost('name');

            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
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
        if ($this->getRequest()) {
            $currencyMapper = new CurrencyMapper();

            $id = $this->getRequest()->getParam('id');
            if($currencyMapper->getCurrencyById($id)[0]->getId() == $this->getConfig()->get('checkout_currency')) {
                $this->addMessage('currencyInUse', 'danger');
                $this->redirect(['action' => 'index']);
                return;
            }

            $currencyMapper->deleteCurrencyById($id);

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }
    }
}