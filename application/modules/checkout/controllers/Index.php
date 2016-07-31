<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkout\Controllers;

use Modules\Checkout\Mappers\Checkout as CheckoutMapper;
use Modules\Checkout\Mappers\Currency as CurrencyMapper;
use Ilch\Date as IlchDate;

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
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('checkout_currency'))[0];

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('checkout'), ['action' => 'index']);
        $this->getView()->set('checkout', $checkout);
        $this->getView()->set('amount', $amount);
        $this->getView()->set('amountplus', $amountplus);
        $this->getView()->set('amountminus', $amountminus);
        $this->getView()->set('checkout_contact', $this->getConfig()->get('checkout_contact'));
        $this->getView()->set('currency', $currency->getName());
    }
}