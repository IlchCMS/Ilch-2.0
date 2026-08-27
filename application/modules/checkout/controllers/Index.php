<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Checkout\Controllers;

use Modules\Checkout\Mappers\Checkout as CheckoutMapper;
use Modules\Checkout\Mappers\Currency as CurrencyMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $checkoutMapper = new CheckoutMapper();
        $currencyMapper = new CurrencyMapper();

        $checkout = $checkoutMapper->getEntries();
        $amount = $checkoutMapper->getAmount();
        $amountplus = $checkoutMapper->getAmountPlus();
        $amountminus = $checkoutMapper->getAmountMinus();
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('checkout_currency') ?? 0);

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('checkout'), ['action' => 'index']);
        $this->getView()->set('checkout', $checkout)
            ->set('amount', $amount)
            ->set('amountplus', $amountplus)
            ->set('amountminus', $amountminus)
            ->set('checkout_contact', $this->getConfig()->get('checkout_contact'))
            ->set('currency', $currency->getName());
    }
}
