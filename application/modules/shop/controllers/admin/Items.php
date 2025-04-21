<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;
use Modules\Shop\Mappers\Category as CategoryMapper;
use Modules\Shop\Mappers\Currency as CurrencyMapper;
use Modules\Shop\Mappers\Items as ItemsMapper;
use Modules\Shop\Mappers\Orders as OrdersMapper;
use Modules\Shop\Mappers\Settings as SettingsMapper;
use Modules\Shop\Mappers\Properties as PropertiesMapper;
use Modules\Shop\Mappers\Propertytranslations as PropertytranslationsMapper;
use Modules\Shop\Mappers\Propertyvalues as PropertyvaluesMapper;
use Modules\Shop\Mappers\Propertyvaluestranslations as PropertyvaluestranslationsMapper;
use Modules\Shop\Mappers\Propertyvariants as PropertyvariantsMapper;
use Modules\Shop\Models\Item as ItemsModel;
use Ilch\Validation;
use Modules\Shop\Models\Propertyvariant;

class Items extends Admin
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
                'url' => $this->getLayout()->getUrl(['controller' => 'items', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'items', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'menuProperties',
                'active' => false,
                'icon' => 'fa-solid fa-list-check',
                'url' => $this->getLayout()->getUrl(['controller' => 'properties', 'action' => 'index'])
            ],
            [
                'name' => 'menuCustomers',
                'active' => false,
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

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[1][0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuShops',
            $items
        );
    }

    public function indexAction()
    {
        $categoryMapper = new CategoryMapper();
        $currencyMapper = new CurrencyMapper();
        $ordersMapper = new OrdersMapper();
        $itemsMapper = new ItemsMapper();

        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'));

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('menuItems'), ['controller' => 'items', 'action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_shops')) {
            $itemInUse = 0;
            foreach ($this->getRequest()->getPost('check_shops') as $itemId) {
                $itemInUse = 0;
                foreach ($ordersMapper->getOrders() as $val) {
                    $orderItems = $val->getOrderdetails();
                    foreach ($orderItems as $valOrder) {
                        if ($valOrder->getItemId() == $itemId) {
                            $itemInUse = 1;
                        }
                    }
                }
                if ($itemInUse == 0) {
                    $itemsMapper->delete($itemId);
                }
            }
            if ($itemInUse == 0) {
                $this->addMessage('deleteSuccess');
            } else {
                $this->addMessage('deleteItemsFailed', 'danger');
            }
            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('categoryMapper', $categoryMapper);
        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('shopItems', $itemsMapper->getShopItems());
    }

    public function treatAction()
    {
        $categoryMapper = new CategoryMapper();
        $currencyMapper = new CurrencyMapper();
        $itemsMapper = new ItemsMapper();
        $settingsMapper = new SettingsMapper();
        $propertiesMapper = new PropertiesMapper();
        $propertiesTranslationsMapper = new PropertytranslationsMapper();
        $propertyValuesMapper = new PropertyvaluesMapper();
        $propertyvaluestranslationsMapper = new PropertyvaluestranslationsMapper();
        $propertiesVariantsMapper = new PropertyvariantsMapper();

        $propertyVariants = null;
        $currency = $currencyMapper->getCurrencyById($this->getConfig()->get('shop_currency'));

        if ($this->getRequest()->getParam('id')) {
            if (!is_numeric($this->getRequest()->getParam('id'))) {
                $this->addMessage('editItemNotFound', 'danger');
                $this->redirect(['action' => 'index']);
            }

            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuItems'), ['controller' => 'items', 'action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $propertyVariants = $propertiesVariantsMapper->getPropertiesVariants(['item_id' => $this->getRequest()->getParam('id')]);
            $itemIds = [];
            if ($propertyVariants) {
                foreach ($propertyVariants as $propertyVariant) {
                    $itemIds[] = $propertyVariant->getItemVariantId();
                }
            }

            $this->getView()->set('shopItems', $itemIds ? $itemsMapper->getShopItemsByIds($itemIds) : []);
            $this->getView()->set('shopItem', $itemsMapper->getShopItemById($this->getRequest()->getParam('id')));
            $this->getView()->set('propertyVariants', $propertyVariants);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('menuItems'), ['controller' => 'items', 'action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        // Get the properties and their values.
        $properties = $propertiesMapper->getProperties();
        $propertiesIds = array_keys($properties);
        $propertiesValues = $propertiesIds ? $propertyValuesMapper->getValues(['property_id' => $propertiesIds]) : [];

        // Get the translations for the properties.
        $propertiesTranslations = $propertiesIds ? $propertiesTranslationsMapper->getTranslationsByLocaleAndPropertyIds($this->getTranslator()->getLocale(), $propertiesIds) : [];
        $propertyTranslationAssoc = [];
        foreach($propertiesTranslations as $propertyTranslation) {
            $propertyTranslationAssoc[$propertyTranslation->getPropertyId()] = $propertyTranslation->getText();
        }

        // Get the translations for the values.
        $propertyValuesTranslations = $propertiesValues ? $propertyvaluestranslationsMapper->getTranslationsByLocaleAndValueIds($this->getTranslator()->getLocale(), array_keys($propertiesValues)) ?? [] : [];
        $propertyValueTranslationAssoc = [];
        foreach($propertyValuesTranslations as $propertyValueTranslation) {
            $propertyValueTranslationAssoc[$propertyValueTranslation->getValueId()] = $propertyValueTranslation->getText();
        }

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'catId' => 'cat',
            ]);

            $validationRules = [
                'catId' => 'required|numeric|integer|min:1',
                'name' => 'required',
                'stock' => 'required|integer',
                'price' => 'required',
                'shippingCosts' => 'required',
                'shippingTime' => 'required|numeric|integer|min:1'
            ];

            if ($this->getRequest()->getPost('cordon') == '1') {
                $validationRules['cordonColor'] = 'required';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                // Prepare the basic model for the product.
                $model = new ItemsModel();
                $model->setCatId($this->getRequest()->getPost('catId'));
                $model->setName($this->getRequest()->getPost('name'));
                $model->setItemnumber($this->getRequest()->getPost('itemnumber'));
                $model->setStock($this->getRequest()->getPost('stock'));
                $model->setUnitName($this->getRequest()->getPost('unitName'));
                $model->setCordon($this->getRequest()->getPost('cordon'));
                $model->setCordonText($this->getRequest()->getPost('cordonText'));
                $model->setCordonColor($this->getRequest()->getPost('cordonColor'));
                $model->setPrice($this->getRequest()->getPost('price'));
                $model->setTax($this->getRequest()->getPost('tax'));
                $model->setShippingCosts($this->getRequest()->getPost('shippingCosts'));
                $model->setShippingTime($this->getRequest()->getPost('shippingTime'));
                $model->setImage($this->getRequest()->getPost('image'));
                $model->setImage1($this->getRequest()->getPost('image1'));
                $model->setImage2($this->getRequest()->getPost('image2'));
                $model->setImage3($this->getRequest()->getPost('image3'));
                $model->setInfo($this->getRequest()->getPost('info'));
                $model->setDesc($this->getRequest()->getPost('desc'));
                $model->setStatus($this->getRequest()->getPost('status'));

                $variantModel = null;
                if ($this->getRequest()->getPost('valueCheckbox')) {
                    $variantModel = clone($model);
                }
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                }

                $itemId = $itemsMapper->save($model);

                $usedValueIds = [];
                foreach ($propertyVariants as $propertyVariant) {
                    $usedValueIds[$propertyVariant->getValueId()] = $propertyVariant;
                }

                foreach($this->getRequest()->getPost('valueCheckbox') ?? [] as $propertyValue) {
                    if (!isset($usedValueIds[$propertyValue])) {
                        // Variant doesn't already exist. Save the variant as a new product.
                        // Set initial values suitable for a variant of a product.
                        $variantModel->setName($this->getRequest()->getPost('name') . ', ' . $propertiesValues[$propertyValue]->getValue());
                        $variantModel->setItemnumber($this->getRequest()->getPost('itemnumber') . $propertiesValues[$propertyValue]->getValue());
                        $variantModel->setStock(0);
                        $variantModel->setStatus(0);
                        $itemVariantId = $itemsMapper->save($variantModel);

                        // Create variant.
                        $newPropertyVariant = new PropertyVariant();
                        $newPropertyVariant->setItemId($itemId);
                        $newPropertyVariant->setItemVariantId($itemVariantId);
                        $newPropertyVariant->setPropertyId($propertiesValues[$propertyValue]->getPropertyId());
                        $newPropertyVariant->setValueId($propertyValue);
                        $propertiesVariantsMapper->save($newPropertyVariant);
                    }
                }

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'treat', 'id' => $itemId]);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                  ->withInput()
                  ->withErrors($validation->getErrorBag())
                  ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
            }
        }

        $this->getView()->set('cats', $categoryMapper->getCategories());
        $this->getView()->set('categoryMapper', $categoryMapper);
        $this->getView()->set('currency', $currency->getName());
        $this->getView()->set('settings', $settingsMapper->getSettings());
        $this->getView()->set('properties', $properties);
        $this->getView()->set('propertiesTranslations', $propertyTranslationAssoc);
        $this->getView()->set('propertiesValues', $propertiesValues);
        $this->getView()->set('propertiesValuesTranslations', $propertyValueTranslationAssoc);
    }

    public function delShopAction()
    {
        if ($this->getRequest()->isSecure() && is_numeric($this->getRequest()->getParam('id'))) {
            $itemsMapper = new ItemsMapper();
            $ordersMapper = new OrdersMapper();
            $itemInUse = 0;
            foreach ($ordersMapper->getOrders() as $val) {
                $orderItems = $val->getOrderdetails();
                foreach ($orderItems as $valOrder) {
                    if ($valOrder->getItemId() == $this->getRequest()->getParam('id')) {
                        $itemInUse = 1;
                    }
                }
            }
            if ($itemInUse == 0) {
                $itemsMapper->delete($this->getRequest()->getParam('id'));
                $this->addMessage('deleteSuccess');
            } else {
                $this->addMessage('deleteItemFailed', 'danger');
            }
        } else {
            $this->addMessage('deleteItemFailedNotFound', 'danger');
        }
        $this->redirect(['action' => 'index']);
    }
}
