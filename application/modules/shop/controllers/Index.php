<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers;

use Ilch\Controller\Frontend;
use Ilch\Date;
use Ilch\Mail;
use Modules\Shop\Mappers\Address as AddressMapper;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Shop\Mappers\Category as CategoryMapper;
use Modules\Shop\Mappers\Customer as CustomerMapper;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\Shop\Mappers\Properties as PropertiesMapper;
use Modules\Shop\Mappers\Propertyvariants as PropertyvariantsMapper;
use Modules\Shop\Mappers\Propertyvalues as PropertyvaluesMapper;
use Modules\Shop\Mappers\Propertytranslations as PropertytranslationsMapper;
use Modules\Shop\Mappers\Propertyvaluestranslations as PropertyvaluestranslationsMapper;
use Modules\Shop\Models\Order as OrdersModel;
use Modules\Shop\Models\Orderdetails as OrderdetailsModel;
use Modules\Shop\Models\Customer as CustomerModel;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Index extends Frontend
{
    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $userMapper = new UserMapper();

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'));

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $categories = $categoryMapper->getCategoriesByAccess($readAccess);
        $catIds = [];
        foreach ($categories as $category) {
            $catIds[] = $category->getId();
        }
        $countCats = $itemsMapper->getCountOfItemsPerCategory($catIds);
        $countAllItems = $itemsMapper->getCountOfItems();

        if ($this->getRequest()->getParam('catId') && is_numeric($this->getRequest()->getParam('catId'))) {
            $category = $categoryMapper->getCategoryById($this->getRequest()->getParam('catId'));

            if (!$category) {
                $this->redirect(['action' => 'index']);
            }

            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('menuShops'));
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);

            if (is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
                $this->getLayout()->header()->css('static/css/style_front.css');
                $this->getLayout()->getTitle()
                    ->add($category->getTitle());
                $this->getLayout()->getHmenu()
                    ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()]);
            } else {
                $this->redirect(['action' => 'index']);
            }

            $shopItems = $itemsMapper->getShopItems(['cat_id' => $this->getRequest()->getParam('catId'), 'status' => 1]);
        } elseif ($this->getRequest()->getParam('catId') && $this->getRequest()->getParam('catId') == 'all') {
            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('menuShops'))
                ->add($this->getTranslator()->trans('allProducts'));
            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('allProducts'), ['action' => 'index', 'catId' => 'all']);
            $shopItems = $itemsMapper->getShopItems(['status' => 1]);
        } elseif (!empty($categories)) {
            $this->getLayout()->header()->css('static/css/style_front.css');
            $shopItems = $itemsMapper->getShopItems(['cat_id' => $categories[0]->getId(), 'status' => 1]);
            if (empty($shopItems)) {
                $this->getLayout()->getTitle()
                    ->add($this->getTranslator()->trans('menuShops'))
                    ->add($this->getTranslator()->trans('allProducts'));
                $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('allProducts'), ['action' => 'index', 'catId' => 'all']);
                $shopItems = $itemsMapper->getShopItems(['status' => 1]);
            } else {
                $this->getLayout()->getTitle()
                    ->add($this->getTranslator()->trans('menuShops'))
                    ->add($categories[0]->getTitle());
                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                    ->add($categories[0]->getTitle(), ['action' => 'index', 'catId' => $categories[0]->getId()]);
                $this->getView()->set('firstCatId', $categories[0]->getId());
            }
        } else {
            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('menuShops'));
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);
            $shopItems = $itemsMapper->getShopItems(['status' => 1]);
        }

        $this->getView()->set('categories', $categories);
        $this->getView()->set('countAllItems', $countAllItems);
        $this->getView()->set('countCats', $countCats);
        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('itemsMapper', $itemsMapper);
        $this->getView()->set('shopItems', $shopItems);
    }

    public function cartAction()
    {
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $settingsMapper = new SettingsMapper();
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'));

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuShops'))
            ->add($this->getTranslator()->trans('menuCart'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuCart'), ['action' => 'cart']);

        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('itemsMapper', $itemsMapper);
        $this->getView()->set('allowWillCollect', $settingsMapper->getSettings()->getAllowWillCollect());
    }

    public function agbAction()
    {
        $settingsMapper = new SettingsMapper();

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuShops'))
            ->add($this->getTranslator()->trans('menuAGB'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuAGB'), ['action' => 'agb']);

        $this->getView()->set('shopSettings', $settingsMapper->getSettings());
    }

    public function orderAction()
    {
        $addressMapper = new AddressMapper();
        $emailsMapper = new EmailsMapper();
        $customerMapper = new CustomerMapper();
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $ordersMapper = new OrdersMapper();
        $settingsMapper = new SettingsMapper();
        $ilchDate = new Date();
        $captchaNeeded = captchaNeeded();
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'));
        $addresses = [];
        $customer = null;

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuShops'))
            ->add($this->getTranslator()->trans('menuCart'))
            ->add($this->getTranslator()->trans('menuOrder'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuCart'), ['action' => 'cart'])
            ->add($this->getTranslator()->trans('menuOrder'), ['action' => 'order']);

        if (!empty($this->getUser())) {
            $customer = $customerMapper->getCustomerByUserId($this->getUser()->getId());
        }

        if (!empty($customer)) {
            $addresses = $addressMapper->getAddressesByCustomerId($customer->getId());
        }

        if ($this->getRequest()->getPost('saveOrder')) {
            $validationRules = [
                'prename' => 'required',
                'lastname' => 'required',
                'street' => 'required',
                'postcode' => 'required',
                'city' => 'required',
                'email' => 'required|email',
                'acceptOrder' => 'required',
                'willCollect' => 'required|integer|min:0|max:1'
            ];

            if ($this->getRequest()->getPost('dropdownDeliveryAddress')) {
                $validationRules = ['dropdownDeliveryAddress' => 'required|numeric'];
            }

            if ($this->getRequest()->getPost('differentInvoiceAddress')) {
                if ($this->getRequest()->getPost('dropdownInvoiceAddress')) {
                    $validationRules['dropdownInvoiceAddress'] = 'required|numeric';
                } else {
                    $validationRules['invoiceAddressPrename'] = 'required';
                    $validationRules['invoiceAddressLastname'] = 'required';
                    $validationRules['invoiceAddressStreet'] = 'required';
                    $validationRules['invoiceAddressPostcode'] = 'required|numeric|integer';
                    $validationRules['invoiceAddressCity'] = 'required';
                }
            }

            if ($captchaNeeded) {
                $validationRules['captcha'] = 'captcha';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);
            if ($validation->isValid()) {
                if (!$settingsMapper->getSettings()->getAllowWillCollect() && $this->getRequest()->getPost('willCollect')) {
                    // Either the will collect feature got disabled between the order view and the customers click on "complete purchase"
                    // or we are dealing with malicious intent. Redirect with error.
                    $this->addMessage('InvalidOrderWillCollectDisabled', 'danger');
                    $this->redirect()
                        ->withInput()
                        ->to(['action' => 'order']);
                }

                $arrayOrder = json_decode(str_replace("'", '"', $this->getRequest()->getPost('order')), true);
                $arrayOrderValid = true;

                // Validate the details of the order and create an array of IDs to later fetch the item details.
                $validationRules = [
                    'id' => 'required|integer',
                    'quantity' => 'required|integer'
                ];

                $itemIds = [];
                foreach ($arrayOrder as $orderItem) {
                    $validation = Validation::create($orderItem, $validationRules);
                    if (!$validation->isValid()) {
                        $arrayOrderValid = false;
                        break;
                    }
                    $itemIds[] = $orderItem['id'];
                }

                if ($arrayOrderValid) {
                    $model = new OrdersModel();
                    $model->setDatetime($ilchDate->toDb());
                    $model->setCurrencyId($currency->getId());

                    if ($this->getRequest()->getPost('dropdownDeliveryAddress')) {
                        // Don't use possible user input. Get the address from the database.
                        if (empty($customer)) {
                            // Pretends to select a known address of him, but isn't a customer? Redirect with error message.
                            $this->addMessage('unknownCustomer', 'danger');
                            $this->redirect()
                                ->to(['action' => 'order']);
                        }
                        $address = $addressMapper->getAddresses(['id' => $this->getRequest()->getPost('dropdownDeliveryAddress'), 'customerId' => $customer->getId()])[0];

                        $model->getDeliveryAddress()->setId($address->getId());
                        $model->getDeliveryAddress()->setPrename($address->getPrename());
                        $model->getDeliveryAddress()->setLastname($address->getLastname());
                        $model->getDeliveryAddress()->setStreet($address->getStreet());
                        $model->getDeliveryAddress()->setPostcode($address->getPostcode());
                        $model->getDeliveryAddress()->setCity($address->getCity());
                        $model->getDeliveryAddress()->setCountry($address->getCountry());
                    } else {
                        $model->getDeliveryAddress()->setPrename($this->getRequest()->getPost('prename'));
                        $model->getDeliveryAddress()->setLastname($this->getRequest()->getPost('lastname'));
                        $model->getDeliveryAddress()->setStreet($this->getRequest()->getPost('street'));
                        $model->getDeliveryAddress()->setPostcode($this->getRequest()->getPost('postcode'));
                        $model->getDeliveryAddress()->setCity($this->getRequest()->getPost('city'));
                        $model->getDeliveryAddress()->setCountry($this->getRequest()->getPost('country'));
                    }

                    $model->setEmail($this->getRequest()->getPost('email'));

                    $items = $itemsMapper->getShopItemsByIds($itemIds);
                    $itemDetails = [];
                    foreach ($items as $item) {
                        $itemDetails[$item->getId()]['price'] = $item->getPrice();
                        $itemDetails[$item->getId()]['tax'] = $item->getTax();
                        $itemDetails[$item->getId()]['shippingCosts'] = $item->getShippingCosts();
                    }

                    $orderdetails = [];
                    foreach ($arrayOrder as $orderItem) {
                        if (!isset($itemDetails[$orderItem['id']])) {
                            // Looks like details for this product are missing. Product likely got deleted.
                            $this->redirect()
                                ->withInput()
                                ->to(['action' => 'order']);
                        }

                        $orderdetail = new OrderdetailsModel();
                        // orderId is unknown at this point and gets added in the save function of the order mapper.
                        $orderdetail->setItemId($orderItem['id']);
                        $orderdetail->setPrice($itemDetails[$orderItem['id']]['price']);
                        $orderdetail->setQuantity($orderItem['quantity']);
                        $orderdetail->setTax($itemDetails[$orderItem['id']]['tax']);
                        $orderdetail->setShippingCosts($itemDetails[$orderItem['id']]['shippingCosts']);
                        $orderdetails[] = $orderdetail;
                    }
                    $model->setOrderdetails($orderdetails);

                    if ($this->getRequest()->getPost('differentInvoiceAddress')) {
                        if ($this->getRequest()->getPost('dropdownInvoiceAddress')) {
                            // Don't use possible user input. Get the address from the database.
                            if (empty($customer)) {
                                // Pretends to select a known address of him, but isn't a customer? Redirect with error message.
                                $this->addMessage('unknownCustomer', 'danger');
                                $this->redirect()
                                    ->to(['action' => 'order']);
                            }
                            $address = $addressMapper->getAddresses(['id' => $this->getRequest()->getPost('dropdownInvoiceAddress'), 'customerId' => $customer->getId()])[0];

                            $model->getInvoiceAddress()->setId($address->getId());
                            $model->getInvoiceAddress()->setPrename($address->getPrename());
                            $model->getInvoiceAddress()->setLastname($address->getLastname());
                            $model->getInvoiceAddress()->setStreet($address->getStreet());
                            $model->getInvoiceAddress()->setPostcode($address->getPostcode());
                            $model->getInvoiceAddress()->setCity($address->getCity());
                            $model->getInvoiceAddress()->setCountry($address->getCountry());
                        } else {
                            $model->getInvoiceAddress()->setPrename($this->getRequest()->getPost('invoiceAddressPrename'));
                            $model->getInvoiceAddress()->setLastname($this->getRequest()->getPost('invoiceAddressLastname'));
                            $model->getInvoiceAddress()->setStreet($this->getRequest()->getPost('invoiceAddressStreet'));
                            $model->getInvoiceAddress()->setPostcode($this->getRequest()->getPost('invoiceAddressPostcode'));
                            $model->getInvoiceAddress()->setCity($this->getRequest()->getPost('invoiceAddressCity'));
                            $model->getInvoiceAddress()->setCountry($this->getRequest()->getPost('invoiceAddressCountry'));
                        }
                    } else {
                        $model->setInvoiceAddress($model->getDeliveryAddress());
                    }

                    // Check if stock is sufficient for this order. Get stock of all items with one query to save queries.
                    $itemIds = [];
                    foreach ($arrayOrder as $product) {
                        $itemIds[] = $product['id'];
                    }
                    $items = $itemsMapper->getShopItemsByIds($itemIds);

                    $itemsAssoc = [];
                    foreach ($items as $item) {
                        $itemsAssoc[$item->getId()] = $item;
                    }

                    $messages = [];
                    foreach ($arrayOrder as $product) {
                        $item = $itemsAssoc[$product['id']];

                        if ($item->getStock() < $product['quantity']) {
                            $messages[] = $this->getTranslator()->trans('currentStockInsufficientDetails', $item->getName(), $item->getStock(), $item->getUnitName());
                        }
                    }

                    if (!empty($messages)) {
                        $this->addMessage($messages, 'danger', true);
                        $this->redirect()
                            ->withInput()
                            ->withErrors($validation->getErrorBag())
                            ->to(['action' => 'cart']);
                    }

                    if (empty($customer)) {
                        // Add the user as a new customer.
                        $customer = new CustomerModel();
                        $customer->setEmail($this->getUser()->getEmail());
                        $customer->setUserId($this->getUser()->getId());
                        $customer->setId($customerMapper->save($customer));
                    } elseif ($customer->getEmail() !== $this->getUser()->getEmail()) {
                        // The customers email address changed. Update it.
                        $customer->setEmail($this->getUser()->getEmail());
                        $customerMapper->save($customer);
                    }

                    $model->setCustomerId($customer->getId());
                    $model->getDeliveryAddress()->setCustomerID($customer->getId());
                    $model->getInvoiceAddress()->setCustomerID($customer->getId());
                    $model->setWillCollect($this->getRequest()->getPost('willCollect'));
                    $ordersMapper->save($model);

                    foreach ($arrayOrder as $product) {
                        $itemsMapper->removeStock($product['id'], $product['quantity']);
                    }

                    // Send confirmation email.
                    $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
                    $date = new Date();
                    $mailContent = $emailsMapper->getEmail('shop', 'order_confirmed_mail', $this->getTranslator()->getLocale());
                    $prename = $this->getLayout()->escape($model->getDeliveryAddress()->getPrename());
                    $lastname = $this->getLayout()->escape($model->getDeliveryAddress()->getLastname());
                    $name = $prename . ' ' . $lastname;

                    $layout = $_SESSION['layout'] ?? '';

                    if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/shop/layouts/mail/orderconfirmed.php')) {
                        $messageTemplate = file_get_contents(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/shop/layouts/mail/orderconfirmed.php');
                    } else {
                        $messageTemplate = file_get_contents(APPLICATION_PATH . '/modules/shop/layouts/mail/orderconfirmed.php');
                    }
                    $messageReplace = [
                        '{content}' => $this->getLayout()->purify($mailContent->getText()),
                        '{shopname}' => $this->getLayout()->escape($settingsMapper->getSettings()->getShopName()),
                        '{date}' => $date->format('l, d. F Y', true),
                        '{name}' => $name,
                        '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                    ];
                    $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                    $mail = new Mail();
                    $mail->setFromName($siteTitle)
                        ->setFromEmail($this->getConfig()->get('standardMail'))
                        ->setToName($name)
                        ->setToEmail($this->getRequest()->getPost('email'))
                        ->setSubject($this->getLayout()->purify($mailContent->getDesc()))
                        ->setMessage($message)
                        ->send();

                    $this->redirect()
                        ->to(['action' => 'success']);
                } else {
                    $this->addMessage('invalidOrder', 'danger');
                    $this->redirect()
                        ->to(['action' => 'order']);
                }
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'order']);
            }
        }

        $this->getView()->set('addresses', $addresses);
        $this->getView()->set('captchaNeeded', $captchaNeeded);
        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('itemsMapper', $itemsMapper);
        $this->getView()->set('allowWillCollect', $settingsMapper->getSettings()->getAllowWillCollect());
        $this->getView()->set('regist_accept', $this->getConfig()->get('regist_accept'));
    }

    public function successAction()
    {
        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuShops'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);
    }

    public function showAction()
    {
        $categoryMapper = new CategoryMapper();
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $userMapper = new UserMapper();
        $propertiesMapper = new PropertiesMapper();
        $propertyVariantsMapper = new PropertyvariantsMapper();
        $propertyValuesMapper = new PropertyValuesMapper();
        $propertyTranslationMapper = new PropertyTranslationsMapper();
        $propertyValuesTranslationsMapper = new PropertyvaluestranslationsMapper();

        if (empty($this->getRequest()->getParam('id')) || !is_numeric($this->getRequest()->getParam('id'))) {
            $this->redirect(['action' => 'index']);
        }

        $shopItem = $itemsMapper->getShopItemById($this->getRequest()->getParam('id'));

        if (empty($shopItem) || $shopItem->getStatus() != 1) {
            $this->redirect(['action' => 'index']);
        }

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'));
        $category = $categoryMapper->getCategoryById($shopItem->getCatId());

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getLayout()->header()->css('static/css/style_front.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuShops'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index']);

        if (is_in_array($readAccess, explode(',', $category->getReadAccess()))) {
            $this->getLayout()->header()->css('static/css/style_front.css');
            $this->getLayout()->getTitle()
                ->add($category->getTitle())
                ->add($shopItem->getName());
            $this->getLayout()->getHmenu()
                ->add($category->getTitle(), ['action' => 'index', 'catId' => $category->getId()])
                ->add($shopItem->getName(), ['action' => 'show', 'id' => $shopItem->getId()]);
        } else {
            $this->redirect(['action' => 'index']);
        }

        $propertyVariants = $propertyVariantsMapper->getPropertiesVariants(['item_id' => $this->getRequest()->getParam('id')]);

        // Get the other variants of the parent item/product if the previous query didn't return a result and this is a variant.
        if (!$propertyVariants && $shopItem->isVariant()) {
            // Get the item id of this variant to get other variants with the same item id.
            $propertyVariants = $propertyVariantsMapper->getPropertiesVariants(['item_variant_id' => $this->getRequest()->getParam('id')]);
            $propertyVariants = $propertyVariantsMapper->getPropertiesVariants(['item_id' => reset($propertyVariants)->getItemId()]);
        }

        // Remove variants that are disabled and therefore shall not be shown.
        if ($propertyVariants) {
            $itemVariantIds = [];
            foreach ($propertyVariants as $propertyVariant) {
                $itemVariantIds[] = $propertyVariant->getItemVariantId();
            }
            $items = $itemsMapper->getShopItems(['items.id' => $itemVariantIds, 'items.status' => 1]);

            foreach($propertyVariants as $propertyVariant) {
                if (!isset($items[$propertyVariant->getItemVariantId()])) {
                    unset($propertyVariants[$propertyVariant->getId()]);
                }
            }
        }

        $propertyIds = [];
        $valueIds = [];
        $variants = [];

        foreach ($propertyVariants as $propertyVariant) {
            $propertyIds[] = $propertyVariant->getPropertyId();
            $valueIds[] = $propertyVariant->getValueId();
        }

        if ($propertyVariants) {
            $properties = $propertiesMapper->getProperties(['id' => $propertyIds]);
            $propertyValues = $propertyValuesMapper->getValues(['property_id' => $propertyIds]);

            // Get the translations for the values.
            $propertyTranslations = $propertyTranslationMapper->getTranslationsByLocaleAndPropertyIds($this->getTranslator()->getLocale(), $propertyIds);
            $propertyValuesTranslations = $propertyValuesTranslationsMapper->getTranslationsByLocaleAndValueIds($this->getTranslator()->getLocale(), $valueIds) ?? [];

            foreach ($propertyVariants as $propertyVariant) {
                if (!$propertyTranslations) {
                    // Fallback to the name of the property if no translations were found.
                    $variants[$propertyVariant->getItemVariantId()]['property'] ?? $variants[$propertyVariant->getItemVariantId()]['property'] = $properties[$propertyVariant->getPropertyId()]->getName();
                }

                foreach($propertyTranslations as $propertyTranslation) {
                    if ($propertyTranslation->getPropertyId() == $propertyVariant->getPropertyId()) {
                        $variants[$propertyVariant->getItemVariantId()]['property'] = $propertyTranslation->getText();
                        break;
                    }

                    // Fallback to the name of the property if no translation was found.
                    $variants[$propertyVariant->getItemVariantId()]['property'] ?? $variants[$propertyVariant->getItemVariantId()]['property'] = $properties[$propertyVariant->getPropertyId()]->getName();
                }

                if (!$propertyValuesTranslations) {
                    // Fallback to the value of the property if no translations were found.
                    $variants[$propertyVariant->getItemVariantId()]['value'] ?? $variants[$propertyVariant->getItemVariantId()]['value'] = $propertyValues[$propertyVariant->getValueId()]->getValue();
                }

                foreach($propertyValuesTranslations as $propertyValueTranslation) {
                    if ($propertyValueTranslation->getValueId() == $propertyVariant->getValueId()) {
                        $variants[$propertyVariant->getItemVariantId()]['value'] = $propertyValueTranslation->getText();
                        break;
                    }

                    // Fallback to the value of the property if no translation was found.
                    $variants[$propertyVariant->getItemVariantId()]['value'] ?? $variants[$propertyVariant->getItemVariantId()]['value'] = $propertyValues[$propertyVariant->getValueId()]->getValue();
                }
            }
        }

        $this->getView()->set('shopItem', $shopItem);
        $this->getView()->set('variants', $variants);
        $this->getView()->set('currency', $currency->getName());
    }
}
