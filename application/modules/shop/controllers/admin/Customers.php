<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\User\Mappers\User as UserMapper;
use Modules\Shop\Mappers\Customer as CustomerMapper;
use Modules\Shop\Mappers\Address as AddressMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;

class Customers extends Admin
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
                'active' => true,
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

        $this->getLayout()->addMenu(
            'menuCustomers',
            $items
        );
    }

    public function indexAction()
    {
        $customerMapper = new CustomerMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCustomers'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_customers') as $customerId) {
                $customerMapper->delete($customerId);
            }

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('customers', $customerMapper->getCustomers());
    }

    public function showAction()
    {
        $customerMapper = new CustomerMapper();
        $userMapper = new UserMapper();
        $addressMapper = new AddressMapper();
        $ordersMapper = new OrdersMapper();
        $customer = null;

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuCustomers'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuCustomer'), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        if ($this->getRequest()->getParam('id') && is_numeric($this->getRequest()->getParam('id'))) {
            $customer = $customerMapper->getCustomerById($this->getRequest()->getParam('id'));
        }

        if (!$customer) {
            $this->addMessage('customerNotFound', 'danger');
            $this->redirect(['action' => 'index']);
        }

        $user = $userMapper->getUserById($customer->getUserId());

        $this->getView()->set('customer', $customer);
        $this->getView()->set('customerUsername', (!empty($user) ? $user->getName() : ''));
        $this->getView()->set('addresses', $addressMapper->getAddressesByCustomerId($this->getRequest()->getParam('id')));
        $this->getView()->set('orders', $ordersMapper->getOrdersByCustomerId($this->getRequest()->getParam('id')));
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure() && $this->getRequest()->getParam('id') && is_numeric($this->getRequest()->getParam('id'))) {
            $customerMapper = new CustomerMapper();

            $customerMapper->delete($this->getRequest()->getParam('id'));
            $this->addMessage('deleteSuccess');
        } else {
            $this->addMessage('deleteCustomerFailed', 'danger');
        }

        $this->redirect(['action' => 'index']);
    }
}
