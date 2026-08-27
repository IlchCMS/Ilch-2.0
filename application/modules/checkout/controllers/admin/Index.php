<?php

/**
 * @copyright Ilch 2
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
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'currencies',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'settings'])
            ]
        ];

        if ($this->getRequest()->getActionName() == 'settings') {
            $items[2]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'checkout',
            $items
        );
    }

    public function indexAction()
    {
        $ilchdate = new IlchDate();
        $checkoutMapper = new CheckoutMapper();
        $currencyMapper = new CurrencyMapper();

        $checkoutCurrency = $this->getConfig()->get('checkout_currency') ?? 0;

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $_POST['usage'] = trim($this->getRequest()->getPost('usage'));

            $validation = Validation::create($this->getRequest()->getPost(), [
                'name' => 'required',
                'datetime' => 'required|date:Y-m-d H\:i\:s',
                'usage' => 'required',
                'amount' => 'required|numeric'
            ]);

            if ($validation->isValid()) {
                $model = new \Modules\Checkout\Models\Entry();
                $model->setName($this->getRequest()->getPost('name'))
                    ->setDatetime($this->getRequest()->getPost('datetime'))
                    ->setUsage($this->getRequest()->getPost('usage'))
                    ->setAmount($this->getRequest()->getPost('amount'));
                $checkoutMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput($this->getRequest()->getPost())
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'index']);
            }
        }

        $currency = $currencyMapper->getCurrencyById($checkoutCurrency);

        $this->getView()->set('checkout', $checkoutMapper->getEntries())
            ->set('checkoutdate', $ilchdate->toDb())
            ->set('amount', $checkoutMapper->getAmount())
            ->set('amountplus', $checkoutMapper->getAmountPlus())
            ->set('amountminus', $checkoutMapper->getAmountMinus())
            ->set('checkoutCurrency', $checkoutCurrency)
            ->set('currency', $currency->getName());
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
                $this->getConfig()->set('checkout_contact', $this->getRequest()->getPost('checkoutContact'))
                    ->set('checkout_currency', $this->getRequest()->getPost('checkoutCurrency'));
                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'settings']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'settings']);
            }
        }

        $this->getView()->set('currencies', $currencyMapper->getCurrencies())
            ->set('checkoutContact', $this->getConfig()->get('checkout_contact'))
            ->set('checkoutCurrency', $this->getConfig()->get('checkout_currency'));
    }

    public function treatPaymentAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('treatpayment'), ['action' => 'treatpayment', 'id' => $this->getRequest()->getParam('id')]);

        $checkoutMapper = new CheckoutMapper();

        $model = null;
        if ($this->getRequest()->getParam('id')) {
            $model = $checkoutMapper->getEntryById($this->getRequest()->getParam('id'));
        }

        if (!$model) {
            $this->redirect()
                ->withMessage('notfound', 'danger')
                ->to(['action' => 'index']);
        }

        if ($this->getRequest()->isPost() && $model->getId()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'name' => 'required',
                'datetime' => 'required|date:Y-m-d H\:i\:s',
                'usage' => 'required',
                'amount' => 'required|numeric',
            ]);

            if ($validation->isValid()) {
                $model->setName($this->getRequest()->getPost('name'))
                    ->setDatetime($this->getRequest()->getPost('datetime'))
                    ->setUsage($this->getRequest()->getPost('usage'))
                    ->setAmount($this->getRequest()->getPost('amount'));
                $checkoutMapper->save($model);

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput($this->getRequest()->getPost())
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'treatPayment', 'id' => $model->getId()]);
            }
        }

        $this->getView()->set('checkout', $model);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $checkoutMapper = new CheckoutMapper();
            $id = $this->getRequest()->getParam('id');
            $checkoutMapper->deleteById($id);

            $this->addMessage('deleteSuccess');
        }
        $this->redirect(['action' => 'index']);
    }
}
