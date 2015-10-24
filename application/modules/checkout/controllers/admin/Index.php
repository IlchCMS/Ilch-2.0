<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Checkout\Controllers\Admin;

use Modules\Checkout\Mappers\Checkout as CheckoutMapper;
use Ilch\Date as IlchDate;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'checkout',
            array
            (
                array
                (
                    'name' => 'Verwalten',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
                array
                (
                    'name' => 'accountdata',
                    'active' => false,
                    'icon' => 'fa fa-cogs',
                    'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'settings'))
                )
            )
        );
    }

    public function indexAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), array('action' => 'index'));

        $ilchdate = new IlchDate;
        $checkoutMapper = new CheckoutMapper();

        if ($this->getRequest()->isPost()) {
            $name = $this->getRequest()->getPost('name');
            $datetime = trim($this->getRequest()->getPost('datetime'));
            $usage = trim($this->getRequest()->getPost('usage'));
            $amount = trim($this->getRequest()->getPost('amount'));

            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
            } elseif(empty($usage)) {
                $this->addMessage('missingUsage', 'danger');
            } elseif(empty($amount)) {
                $this->addMessage('missingAmount', 'danger');
            } else {
                $model = new \Modules\Checkout\Models\Entry();
                $model->setName($name);
                $model->setDatetime($datetime);
                $model->setUsage($usage);
                $model->setAmount($amount);
                $checkoutMapper->save($model);

                $this->addMessage('saveSuccess');
            }
        }

        $checkout = $checkoutMapper->getEntries();
        $amount = $checkoutMapper->getAmount();
        $amountplus = $checkoutMapper->getAmountPlus();
        $amountminus = $checkoutMapper->getAmountMinus();

        $this->getView()->set('checkout', $checkout);
        $this->getView()->set('checkoutdate', $ilchdate->toDb());
        $this->getView()->set('amount', $amount);
        $this->getView()->set('amountplus', $amountplus);
        $this->getView()->set('amountminus', $amountminus);
    }

    public function settingsAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('settings'), array('action' => 'settings'));

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('checkout_contact', $this->getRequest()->getPost('checkout_contact'));
            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('checkout_contact', $this->getConfig()->get('checkout_contact'));

    }

    public function treatPaymentAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('checkout'), array('action' => 'index'))
                ->add($this->getTranslator()->trans('treatpayment'), array('action' => 'treatpayment', 'id' => $this->getRequest()->getParam('id')));

        $checkoutMapper = new CheckoutMapper();
        $id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->isPost()) {
            $name = $this->getRequest()->getPost('name');
            $datetime = trim($this->getRequest()->getPost('datetime'));
            $usage = trim($this->getRequest()->getPost('usage'));
            $amount = trim($this->getRequest()->getPost('amount'));
            $id = trim($this->getRequest()->getPost('id'));

            if (empty($name)) {
                $this->addMessage('missingName', 'danger');
            } elseif(empty($usage)) {
                $this->addMessage('missingUsage', 'danger');
            } elseif(empty($amount)) {
                $this->addMessage('missingAmount', 'danger');
            } else {
                $model = new \Modules\Checkout\Models\Entry();
                $model->setId($id);
                $model->setName($name);
                $model->setDatetime($datetime);
                $model->setUsage($usage);
                $model->setAmount($amount);
                $checkoutMapper->save($model);

                $this->addMessage('saveSuccess');
            }
        }

        $this->getView()->set('checkout', $checkoutMapper->getEntryById($id));
    }

    public function delAction()
    {
        if ($this->getRequest()) {
            $checkoutMapper = new CheckoutMapper();
            $id = $this->getRequest()->getParam('id');
            $checkoutMapper->deleteById($id);

            $this->addMessage('deleteSuccess');
            $this->redirect(array('action' => 'index'));
        }
    }
}