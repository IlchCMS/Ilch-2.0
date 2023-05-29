<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Models\Currency as CurrencyModel;
use Ilch\Validation;

class Currency extends Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuOverview',
                'active' => false,
                'icon' => 'fa-solid fa-shop',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuItems',
                'active' => false,
                'icon' => 'fa-solid fa-tshirt',
                'url' => $this->getLayout()->getUrl(['controller' => 'items', 'action' => 'index'])
            ],
            [
                'name' => 'menuCustomers',
                'active' => false,
                'icon' => 'fa-solid fa-users',
                'url' => $this->getLayout()->getUrl(['controller' => 'customers', 'action' => 'index'])
            ],
            [
                'name' => 'menuOrders',
                'active' => false,
                'icon' => 'fa-solid fa-cart-arrow-down',
                'url' => $this->getLayout()->getUrl(['controller' => 'orders', 'action' => 'index'])
            ],
            [
                'name' => 'menuCats',
                'active' => false,
                'icon' => 'fa-solid fa-rectangle-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'cats', 'action' => 'index'])
            ],
            [
                'name' => 'menuCurrencies',
                'active' => false,
                'icon' => 'fa-solid fa-money-bill-1',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuNote',
                'active' => false,
                'icon' => 'fa-solid fa-circle-info',
                'url' => $this->getLayout()->getUrl(['controller' => 'note', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[5][0]['active'] = true;
        } else {
            $items[5]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuCurrencies',
            $items
        );
    }

    public function indexAction()
    {
        $currencyMapper = new CurrencyMapper();
        $ordersMapper = new OrdersMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuCurrencies'), ['action' => 'index']);

        if ($this->getRequest()->isPost() && $this->getRequest()->isSecure()) {
            if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_currencies')) {
                $orders = $ordersMapper->getOrders();
                $currencyIds = [];

                foreach($orders as $order) {
                    $currencyIds[] = $order->getCurrencyId();
                }
                $currencyInUse = (in_array($this->getConfig()->get('shop_currency'), $currencyIds) && $currencyMapper->getCurrencyById($id)[0]->getId() == $this->getConfig()->get('shop_currency'));

                foreach ($this->getRequest()->getPost('check_currencies') as $id) {
                    if ($currencyInUse) {
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
        // https://developer.paypal.com/reference/currency-codes/
        $currencyCodesPayPal = ['AUD', 'BRL', 'CAD', 'CNY', 'CZK', 'DKK', 'EUR', 'HKD', 'HUF', 'ILS', 'JPY', 'MYR', 'MXN', 'TWD', 'NZD', 'NOK', 'PHP', 'PLN', 'GBP', 'RUB', 'SGD', 'SEK', 'CHF', 'THB', 'USD'];

        $currencyMapper = new CurrencyMapper();
        $ordersMapper = new OrdersMapper();
        $id = $this->getRequest()->getParam('id');
        $currencyInUse = false;

        if ($this->getRequest()->getParam('id')) {
            if (!is_numeric($this->getRequest()->getParam('id'))) {
                $this->addMessage('editCurrencyFailedNotFound', 'danger');
                $this->redirect(['action' => 'index']);
            }

            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCurrencies'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => 'treat']);

            $orders = $ordersMapper->getOrders();
            $currencyIds = [];

            foreach($orders as $order) {
                $currencyIds[] = $order->getCurrencyId();
            }
            $currencyInUse = (in_array($this->getConfig()->get('shop_currency'), $currencyIds) && $currencyMapper->getCurrencyById($id)[0]->getId() == $this->getConfig()->get('shop_currency'));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('menuCurrencies'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat', 'id' => 'treat']);
        }

        if ($this->getRequest()->isPost() && $this->getRequest()->isSecure()) {
            $post = [
                'id' => $this->getRequest()->getPost('id'),
                'name' => trim($this->getRequest()->getPost('name')),
                'code' => trim($this->getRequest()->getPost('code'))
            ];

            $rules = [
                'name' => 'required',
                'code' => 'required|size:3'
            ];

            if (!in_array(trim($this->getRequest()->getPost('code')), $currencyCodesPayPal)) {
                $this->addMessage($this->getTranslator()->trans('notSupportedForPayPal'), 'warning', true);
            }

            if ($this->getRequest()->getParam('id')) {
                $rules['id'] = 'required|numeric|integer|min:1';
            }
            $validation = Validation::create($post, $rules);

            if ($validation->isValid()) {
                $currencyModel = new CurrencyModel();
                if (!empty($post['id'])) {
                    $currencyModel->setId($post['id']);
                }
                $currencyModel->setName($post['name']);
                $currencyModel->setCode($post['code']);

                $currencyMapper->save($currencyModel);
                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        if ($this->getRequest()->getParam('id')) {
            $currency = $currencyMapper->getCurrencyById($id);
        } else {
            $currency = [];
        }

        if (count($currency) > 0) {
            $currency = $currency[0];
        } else {
            $currency = new CurrencyModel();
        }

        $this->getView()->set('currency', $currency);
        $this->getView()->set('currencyInUse', $currencyInUse);
    }

    public function deleteAction()
    {
        if ($this->getRequest() && $this->getRequest()->isSecure() && $this->getRequest()->getParam('id') && is_numeric($this->getRequest()->getParam('id'))) {
            $currencyMapper = new CurrencyMapper();
            $ordersMapper = new OrdersMapper();

            $id = $this->getRequest()->getParam('id');
            $orders = $ordersMapper->getOrders();
            $currencyIds = [];

            foreach($orders as $order) {
                $currencyIds[] = $order->getCurrencyId();
            }
            $currencyInUse = (in_array($this->getConfig()->get('shop_currency'), $currencyIds) && $currencyMapper->getCurrencyById($id)[0]->getId() == $this->getConfig()->get('shop_currency'));

            if ($currencyInUse) {
                $this->addMessage('currencyInUse', 'danger');
                $this->redirect(['action' => 'index']);
            }

            $currencyMapper->deleteCurrencyById($id);
            $this->addMessage('deleteSuccess');
        } else {
            $this->addMessage('deleteCurrencyFailed', 'danger');
        }
        $this->redirect(['action' => 'index']);
    }
}
