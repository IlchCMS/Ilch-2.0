<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Ilch\Controller\Frontend;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;

class Payment extends Frontend
{
    public function indexAction()
    {
        $settingsMapper = new SettingsMapper();
        $currencyMapper = new CurrencyMapper();
        $ordersMapper = new OrdersMapper();
        $itemsMapper = new ItemsMapper();

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuPayment'), ['controller' => 'payment', 'action' => 'index']);

        if (!$this->getUser()) {
            $this->addMessage('paymentLoginRequired', 'danger');
            $this->redirect(['module' => 'user', 'controller' => 'login', 'action' => 'index']);
        }

        $order = [];

        if (!empty($this->getRequest()->getParam('selector')) && !empty($this->getRequest()->getParam('code'))) {
            $order = $ordersMapper->getOrderBySelector($this->getRequest()->getParam('selector'));
        }

        if (empty($order) || !hash_equals($order->getConfirmCode(), $this->getRequest()->getParam('code'))) {
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
        $this->getView()->set('currency', $currencyMapper->getCurrencyById($order->getCurrencyId())[0]);
    }
}
