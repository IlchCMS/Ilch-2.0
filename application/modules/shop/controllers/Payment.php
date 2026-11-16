<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Ilch\Controller\Frontend;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\Shop\Mappers\Customer as CustomerMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;

class Payment extends Frontend
{
    public function indexAction(): void
    {
        $settingsMapper = new SettingsMapper();
        $currencyMapper = new CurrencyMapper();
        $ordersMapper = new OrdersMapper();
        $customerMapper = new CustomerMapper();
        $itemsMapper = new ItemsMapper();

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuShops'))
            ->add($this->getTranslator()->trans('menuPayment'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuPayment'), ['controller' => 'payment', 'action' => 'index']);

        if (!$this->getUser()) {
            $this->addMessage('paymentLoginRequired', 'danger');
            $this->redirect(['module' => 'user', 'controller' => 'login', 'action' => 'index']);
        }

        // The payment page may only be used for orders that belong to the logged-in user.
        // (Same mapping as Customerarea::showAction(): order.customerId is the shop
        // customer id, which must be resolved via the user id first.)
        $customer = $customerMapper->getCustomerByUserId($this->getUser()->getId());

        $order = [];

        if (!empty($this->getRequest()->getParam('selector')) && !empty($this->getRequest()->getParam('code'))) {
            $order = $ordersMapper->getOrderBySelector($this->getRequest()->getParam('selector'));
        }

        // One generic error message for all failure cases, so the response does not
        // reveal whether the selector exists or belongs to a different customer.
        if (
            empty($customer)
            || empty($order)
            || !hash_equals((string) $order->getConfirmCode(), (string) $this->getRequest()->getParam('code'))
            || $order->getCustomerId() !== $customer->getId()
        ) {
            $this->addMessage('invalidPaymentLink', 'danger');
            $this->redirect(['controller' => 'index', 'action' => 'index']);
        }

        $settings = $settingsMapper->getSettings();

        // Outcomment the check for 'test' for testing purposes of the PayPal payment method.
        if ((!$settings->getClientID() || $settings->getClientID() === 'test') && !$settings->getPayPalMe()) {
            $this->addMessage('paypalNotConfigured', 'danger');
            $this->redirect(['controller' => 'index', 'action' => 'index']);
        }

        $this->getView()->set('itemsMapper', $itemsMapper);
        $this->getView()->set('order', $order);
        $this->getView()->set('settings', $settings);
        $this->getView()->set('currency', $currencyMapper->getCurrencyById($order->getCurrencyId()));
    }
}
