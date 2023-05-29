<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;
use Ilch\Date;
use Ilch\Mail;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\Shop\Models\Order as OrdersModel;

class Orders extends Admin
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
                'active' => true,
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
                'url' => $this->getLayout()->getUrl(['controller' => 'currency', 'action' => 'index'])
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

        $this->getLayout()->addMenu
        (
            'menuOrders',
            $items
        );
    }

    public function indexAction()
    {
        $ordersMapper = new OrdersMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('menuOrders'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_orders')) {
            $orderInUse = 0;
            foreach ($this->getRequest()->getPost('check_orders') as $orderId) {
                if ($ordersMapper->getOrderById($orderId)->getStatus() == 0 || $ordersMapper->getOrderById($orderId)->getStatus() == 1) {
                    $orderInUse++;
                    continue;
                }
                $ordersMapper->delete($orderId);
            }
            if ($orderInUse > 0) {
                $this->addMessage('ordersInUse', 'danger');
            } else {
                $this->addMessage('deleteSuccess');
            }
        }

        $this->getView()->set('ordersMapper', $ordersMapper->getOrders());
    }

    public function treatAction()
    {
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $ordersMapper = new OrdersMapper();
        $settingsMapper = new SettingsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuOrders'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);

        $order = null;

        if ($this->getRequest()->getParam('id') && is_numeric($this->getRequest()->getParam('id'))) {
            $order = $ordersMapper->getOrderById($this->getRequest()->getParam('id'));
            // Get the currency from the order as you don't want a currency change for existing orders.
            $currency = $currencyMapper->getCurrencyById($order->getCurrencyId())[0];
            $this->getView()->set('order', $order);
            $this->getView()->set('currency', $currency->getName());
            $this->getView()->set('itemsMapper', $itemsMapper);
            $this->getView()->set('ordersMapper', $ordersMapper);
            $this->getView()->set('settingsMapper', $settingsMapper);
        }

        if ($this->getRequest()->isPost()) {
            $model = new OrdersModel();
            $stockSufficient = true;

            if ($this->getRequest()->getPost('status') != '') {
                $items = $order->getOrderdetails();

                if ($this->getRequest()->getPost('status') == 2 && $this->getRequest()->getPost('confirmTransferBackToStock') === 'true') {
                    foreach ($items as $item) {
                        $itemsMapper->addStock($item->getItemId(), $item->getQuantity());
                    }
                } elseif ($this->getRequest()->getPost('status') != 2 && $this->getRequest()->getPost('confirmRemoveFromStock') === 'true') {
                    foreach ($items as $item) {
                        $currentStock = $itemsMapper->getShopItemById($item->getItemId());

                        if ($currentStock->getStock() >= $item->getQuantity()) {
                            $itemsMapper->removeStock($item->getItemId(), $item->getQuantity());
                        } else {
                            $stockSufficient = false;
                            $this->addMessage('currentStockInsufficient', 'danger');
                        }
                    }
                }

                if ($stockSufficient) {
                    $model->setId($this->getRequest()->getPost('id'));
                    $model->setStatus($this->getRequest()->getPost('status'));
                    $ordersMapper->updateStatus($model);
                }

                $this->redirect(['action' => 'treat', 'id' => $this->getRequest()->getPost('id')]);
            }

            if ($this->getRequest()->getPost('delete') == 1) {
                if ($ordersMapper->getOrderById($this->getRequest()->getParam('id'))->getStatus() == 0 || $ordersMapper->getOrderById($this->getRequest()->getParam('id'))->getStatus() == 1) {
                    $this->addMessage('orderInUse', 'danger');
                } else {
                    $ordersMapper->delete($this->getRequest()->getParam('id'));
                    $this->addMessage('deleteSuccess');
                    $this->redirect(['action' => 'index']);
                }
            }
        }
    }

    public function downloadAction()
    {
        if (!$this->getRequest()->isSecure()) {
            return;
        }

        set_time_limit(0);
        $shopInvoicePath = ROOT_PATH.'/application/modules/shop/static/invoice/';

        $id = $this->getRequest()->getParam('id');

        if (!empty($id) && is_numeric($id)) {
            $ordersMapper = new OrdersMapper();
            $order = $ordersMapper->getOrderById($id);

            if ($order !== null) {
                $fullPath = $shopInvoicePath.$order->getInvoiceFilename().'.pdf';
                $fd = fopen($fullPath, 'rb');
                if ($fd) {
                    $path_parts = pathinfo($fullPath);
                    // Remove the random part of the filename as it should not end in e.g. the browser history.
                    $publicFileName = preg_replace('/_[^_.]*\./', '.', $path_parts['basename']);

                    header('Content-type: application/pdf');
                    header('Content-Disposition: filename="' .$publicFileName. '"');
                    header('Content-length: ' .filesize($fullPath));
                    // RFC2616 section 14.9.1: Indicates that all or part of the response message is intended for a single user and MUST NOT be cached by a shared cache, such as a proxy server.
                    header('Cache-control: private');
                    while(!feof($fd)) {
                        $buffer = fread($fd, 2048);
                        echo $buffer;
                    }
                } else {
                    $this->addMessage('invoiceNotFound', 'danger');
                }
                fclose($fd);
            }
        } else {
            $this->addMessage('invoiceNotFound', 'danger');
        }

        $this->redirect(['controller' => 'orders', 'action' => 'treat', 'id' => $id]);
    }

    public function sendInvoiceAction()
    {
        if (!$this->getRequest()->isSecure()) {
            return;
        }

        $emailsMapper = new EmailsMapper();
        $orderMapper = new OrdersMapper();
        $settingsMapper = new SettingsMapper();

        $settings = $settingsMapper->getSettings();
        $id = $this->getRequest()->getParam('id');
        $order = $orderMapper->getOrderById($id);

        // Generate selector and confirm code for the payment link.
        $order->setSelector(bin2hex(random_bytes(9)));
        $order->setConfirmCode(bin2hex(random_bytes(32)));

        $shopInvoicePath = '/application/modules/shop/static/invoice/';
        $pathInvoice = ROOT_PATH.$shopInvoicePath.$order->getInvoiceFilename().'.pdf';
        $path_parts = pathinfo($pathInvoice);
        $publicFileNameInvoice = preg_replace('/_[^_.]*\./', '.', $path_parts['basename']);

        // Send invoice to customer.
        $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
        $date = new Date();
        $mailContent = $emailsMapper->getEmail('shop', 'send_invoice_mail', $this->getTranslator()->getLocale());
        $templateName = 'sendinvoice.php';

        if (!$settings->getClientID() && !$settings->getPayPalMe()) {
            // PayPal not configured. Send email without payment link.
            $mailContent = $emailsMapper->getEmail('shop', 'send_invoice_mail_no_paymentlink', $this->getTranslator()->getLocale());
            $templateName = 'sendinvoicenopaymentlink.php';
        }

        $prename = $this->getLayout()->escape($order->getInvoiceAddress()->getPrename());
        $lastname = $this->getLayout()->escape($order->getInvoiceAddress()->getLastname());
        $name = $prename . ' ' . $lastname;

        $layout = $_SESSION['layout'] ?? '';

        if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/shop/layouts/mail/'.$templateName)) {
            $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/shop/layouts/mail/'.$templateName);
        } else {
            $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/shop/layouts/mail/'.$templateName);
        }
        $messageReplace = [
            '{content}' => $this->getLayout()->purify($mailContent->getText()),
            '{shopname}' => $this->getLayout()->escape($settings->getShopName()),
            '{date}' => $date->format('l, d. F Y', true),
            '{name}' => $name,
            '{paymentLink}' => '<a href="'.BASE_URL.'/index.php/shop/payment/index/selector/'.$order->getSelector().'/code/'.$order->getConfirmCode().'">'.$this->getTranslator()->trans('paymentInvoiceLink').'</a>',
            '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
        ];

        $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

        $mail = new Mail();
        $mail->setFromName($siteTitle)
            ->setFromEmail($this->getConfig()->get('standardMail'))
            ->setToName($name)
            ->setToEmail($order->getEmail())
            ->setSubject($this->getLayout()->purify($mailContent->getDesc()))
            ->setMessage($message)
            ->addAttachment($pathInvoice, $publicFileNameInvoice)
            ->send();

        $order->setDatetimeInvoiceSent(new Date('now'));
        $orderMapper->save($order);

        $this->addMessage('sendInvoiceSuccess');
        $this->redirect(['controller' => 'orders', 'action' => 'treat', 'id' => $id]);
    }

    public function delOrderAction()
    {
        if ($this->getRequest()->isSecure()) {
            $ordersMapper = new OrdersMapper();
            if ($ordersMapper->getOrderById($this->getRequest()->getParam('id'))->getStatus() == 0 || $ordersMapper->getOrderById($this->getRequest()->getParam('id'))->getStatus() == 1) {
                $this->addMessage('orderInUse', 'danger');
            } else {
                $ordersMapper->delete($this->getRequest()->getParam('id'));
                $this->addMessage('deleteSuccess');
            }
        }
        $this->redirect(['action' => 'index']);
    }
}
