<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkout\Controllers\Admin;

use Modules\Checkout\Mappers\Checkout as CheckoutMapper;
use Modules\Checkout\Mappers\Currency as CurrencyMapper;
use Ilch\Date as IlchDate;
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
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'settings'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'settings') {
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
        $ilchdate = new IlchDate;
        $checkoutMapper = new CheckoutMapper();
        $currencyMapper = new CurrencyMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        $post = [
            'name' => '',
            'datetime' => '',
            'usage' => '',
            'amount' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'name' => $this->getRequest()->getPost('name'),
                'datetime' => trim($this->getRequest()->getPost('datetime')),
                'usage' => trim($this->getRequest()->getPost('usage')),
                'amount' => trim($this->getRequest()->getPost('amount'))
            ];

            $validation = Validation::create($post, [
                'name' => 'required',
                'datetime' => 'required',
                'usage' => 'required',
                'amount' => 'required|numeric'
            ]);

            if ($validation->isValid()) {
                $model = new \Modules\Checkout\Models\Entry();
                $model->setName($post['name']);
                $model->setDatetime($post['datetime']);
                $model->setUsage($post['usage']);
                $model->setAmount($post['amount']);
                $checkoutMapper->save($model);

                $this->addMessage('saveSuccess');
            }

            $this->redirect()
                ->withInput($post)
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('checkout_currency'))[0];

        $this->getView()->set('checkout', $checkoutMapper->getEntries());
        $this->getView()->set('checkoutdate', $ilchdate->toDb());
        $this->getView()->set('amount', $checkoutMapper->getAmount());
        $this->getView()->set('amountplus', $checkoutMapper->getAmountPlus());
        $this->getView()->set('amountminus', $checkoutMapper->getAmountMinus());
        $this->getView()->set('checkoutCurrency', $this->getConfig()->get('checkout_currency'));
        $this->getView()->set('currency', $currency->getName());
    }

    public function settingsAction()
    {
        $currencyMapper = new CurrencyMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('settings'), ['action' => 'settings']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'checkoutContact' => 'required',
                'checkoutCurrency' => 'required|numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $this->getConfig()->set('checkout_contact', $this->getRequest()->getPost('checkoutContact'));
                $this->getConfig()->set('checkout_currency', $this->getRequest()->getPost('checkoutCurrency'));
                $this->addMessage('saveSuccess');
            }

            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'settings']);
        }

        $this->getView()->set('currencies', $currencyMapper->getCurrencies());
        $this->getView()->set('checkoutContact', $this->getConfig()->get('checkout_contact'));
        $this->getView()->set('checkoutCurrency', $this->getConfig()->get('checkout_currency'));
    }

    public function treatPaymentAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatpayment'), ['action' => 'treatpayment', 'id' => $this->getRequest()->getParam('id')]);

        $checkoutMapper = new CheckoutMapper();
        $id = $this->getRequest()->getParam('id');

        $post = [
            'name' => '',
            'datetime' => '',
            'usage' => '',
            'amount' => '',
            'id' => ''
        ];

        if ($this->getRequest()->isPost()) {
            $post = [
                'name' => $this->getRequest()->getPost('name'),
                'datetime' => trim($this->getRequest()->getPost('datetime')),
                'usage' => trim($this->getRequest()->getPost('usage')),
                'amount' => trim($this->getRequest()->getPost('amount')),
                'id' => trim($this->getRequest()->getPost('id'))
            ];

            $validation = Validation::create($post, [
                'name' => 'required',
                'datetime' => 'required',
                'usage' => 'required',
                'amount' => 'required|numeric',
                'id' => 'required|numeric|integer|min:1'
            ]);

            if ($validation->isValid()) {
                $model = new \Modules\Checkout\Models\Entry();
                $model->setId($post['id']);
                $model->setName($post['name']);
                $model->setDatetime($post['datetime']);
                $model->setUsage($post['usage']);
                $model->setAmount($post['amount']);
                $checkoutMapper->save($model);

                $this->addMessage('saveSuccess');
            }

            $this->redirect()
                ->withInput($post)
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treatPayment', 'id' => $id]);
        }

        $this->getView()->set('checkout', $checkoutMapper->getEntryById($id));
        $this->getView()->set('checkout_currency', $this->getConfig()->get('checkout_currency'));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $checkoutMapper = new CheckoutMapper();
            $id = $this->getRequest()->getParam('id');
            $checkoutMapper->deleteById($id);

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }
    }
}