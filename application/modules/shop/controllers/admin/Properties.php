<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Controllers\Admin;

use Ilch\Controller\Admin;
use Ilch\Validation;
use Modules\Shop\Mappers\Items as ItemsMapper;
use Modules\Shop\Mappers\Properties as PropertiesMapper;
use Modules\Shop\Mappers\Propertytranslations as PropertytranslationsMapper;
use Modules\Shop\Mappers\Propertyvalues as PropertyvaluesMapper;
use Modules\Shop\Mappers\Propertyvaluestranslations as PropertyvaluestranslationsMapper;
use Modules\Shop\Mappers\Propertyvariants as PropertyvariantsMapper;
use Modules\Shop\Models\Property as PropertyModel;
use Modules\Shop\Models\Propertytranslation as PropertytranslationModel;
use Modules\Shop\Models\Propertyvalue as PropertyvalueModel;
use Modules\Shop\Models\Propertyvaluetranslation as PropertyvaluetranslationModel;

class Properties extends Admin
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
                'name' => 'menuProperties',
                'active' => false,
                'icon' => 'fa-solid fa-list-check',
                'url' => $this->getLayout()->getUrl(['controller' => 'properties', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'properties', 'action' => 'treat'])
                ]
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
            $items[2][0]['active'] = true;
        } else {
            $items[2]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuShops',
            $items
        );
    }

    public function indexAction()
    {
        $propertiesMapper = new PropertiesMapper();
        $propertyVariantsMapper = new PropertyvariantsMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuProperties'), ['controller' => 'properties', 'action' => 'index']);

        if ($this->getRequest()->getPost('action') === 'delete' && $this->getRequest()->getPost('check_properties')) {
            $propertyInUse = false;
            foreach ($this->getRequest()->getPost('check_properties') as $propertyId) {
                if (!$propertyVariantsMapper->exists(['property_id' => $propertyId])) {
                    $propertiesMapper->deletePropertyById($propertyId);
                } else {
                    $propertyInUse = true;
                }
            }

            if ($propertyInUse) {
                $this->addMessage('OneOrMorePropertiesInUse', 'danger');
            } else {
                $this->addMessage('deleteSuccess');
            }

            $this->redirect(['action' => 'index']);
        }

        $this->getView()->set('properties', $propertiesMapper->getProperties());
    }

    public function treatAction()
    {
        $propertiesMapper = new PropertiesMapper();
        $propertyTranslationMapper = new PropertytranslationsMapper();
        $propertyValueMapper = new PropertyvaluesMapper();
        $propertyValuesTranslationsMapper = new PropertyvaluestranslationsMapper();
        $propertyVariantsMapper = new PropertyvariantsMapper();

        $propertyId = $this->getRequest()->getParam('id');

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShops'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('menuProperties'), ['controller' => 'properties', 'action' => 'index']);

        if ($propertyId) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'values' => 'propertyValues',
            ]);

            $validationRules = [
                'name' => 'required',
                'values' => 'required'
            ];

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $propertyModel = new PropertyModel();
                $propertyTranslations = null;

                if ($propertyId) {
                    $propertyModel->setId($propertyId);

                    // Get the current existing translations.
                    $propertyTranslations = $propertyTranslationMapper->getTranslationsByPropertyId($propertyId);
                }
                $propertyModel->setName($this->getRequest()->getPost('name'));
                $propertyId = $propertiesMapper->save($propertyModel);

                // Delete no longer existing translations.
                foreach ($propertyTranslations ?? [] as $propertyTranslation) {
                    if (in_array($propertyTranslation->getId(), $this->getRequest()->getPost('propertyTrans_id'))) {
                        continue;
                    }
                    $propertyTranslationMapper->deleteTranslationById($propertyTranslation->getId());
                }

                foreach ($this->getRequest()->getPost('propertyTrans_property_id') as $i => $property_id) {
                    if (!$this->getRequest()->getPost('propertyTrans_locale')[$i] || !$this->getRequest()->getPost('propertyTrans_text')[$i]) {
                        continue;
                    }
                    $propertyTranslationModel = new PropertytranslationModel();
                    if ($this->getRequest()->getPost('propertyTrans_id')[$i]) {
                        $propertyTranslationModel->setId($this->getRequest()->getPost('propertyTrans_id')[$i]);
                    }
                    $propertyTranslationModel->setPropertyId($propertyId);
                    $propertyTranslationModel->setLocale($this->getRequest()->getPost('propertyTrans_locale')[$i]);
                    $propertyTranslationModel->setText($this->getRequest()->getPost('propertyTrans_text')[$i]);
                    $propertyTranslationMapper->save($propertyTranslationModel);
                }

                // Get currently existing property values and delete no longer existing ones.
                // Don't delete values that are currently in use.
                $propertyValues = $propertyValueMapper->getValuesByPropertyId($propertyId);
                $valueInUse = false;
                foreach($propertyValues ?? [] as $propertyValue) {
                    if (in_array($propertyValue->getId(), $this->getRequest()->getPost('values-ids'))) {
                        continue;
                    }
                    if ($propertyVariantsMapper->exists(['value_id' => $propertyValue->getId()])) {
                        $valueInUse = true;
                        continue;
                    }
                    $propertyValueMapper->deleteValueById($propertyValue->getId());
                }

                if ($valueInUse) {
                    $this->addMessage('OneOrMoreValuesInUse', 'danger');
                }

                // Save property values.
                $positions = explode(',', $this->getRequest()->getPost('hiddenMenu'));
                foreach ($this->getRequest()->getPost('values') as $index => $value) {
                    $propertyValueModel = new PropertyvalueModel();
                    if ($this->getRequest()->getPost('values-ids')[$index]) {
                        $propertyValueModel->setId($this->getRequest()->getPost('values-ids')[$index]);
                    }
                    $propertyValueModel->setPropertyId($propertyId);
                    $propertyValueModel->setPosition(array_search($index, $positions));
                    $propertyValueModel->setValue($value);
                    $propertyValueMapper->save($propertyValueModel);
                }

                // Get currently existing property value translations and delete no longer existing ones.
                $propertyValuesTranslations = $propertyValuesTranslationsMapper->getTranslations(['value_id' => array_keys($propertyValues)]);
                foreach($propertyValuesTranslations ?? [] as $propertyValueTranslation) {
                    if (in_array($propertyValueTranslation->getId(), $this->getRequest()->getPost('propertyValueTrans_id'))) {
                        continue;
                    }
                    $propertyValuesTranslationsMapper->deleteTranslationById($propertyValueTranslation->getId());
                }

                // Save property value translations.
                foreach($this->getRequest()->getPost('propertyValueTrans_id') as $i => $value) {
                    if (!$this->getRequest()->getPost('propertyValueTrans_locale')[$i] || !$this->getRequest()->getPost('propertyValueTrans_text')[$i]) {
                        continue;
                    }
                    $propertyValueTranslationModel = new propertyvaluetranslationModel();
                    if ($this->getRequest()->getPost('propertyValueTrans_id')[$i]) {
                        $propertyValueTranslationModel->setId($this->getRequest()->getPost('propertyValueTrans_id')[$i]);
                    }
                    $propertyValueTranslationModel->setValueId($this->getRequest()->getPost('propertyValueTrans_value_id')[$i]);
                    $propertyValueTranslationModel->setLocale($this->getRequest()->getPost('propertyValueTrans_locale')[$i]);
                    $propertyValueTranslationModel->setText($this->getRequest()->getPost('propertyValueTrans_text')[$i]);
                    $propertyValuesTranslationsMapper->save($propertyValueTranslationModel);
                }

                $this->addMessage('saveSuccess');
                $this->redirect(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to($this->getRequest()->getParam('id') ? ['action' => 'treat', 'id' => $this->getRequest()->getParam('id')] : ['action' => 'treat']);
        }

        $values = $propertyId ? $propertyValueMapper->getValuesByPropertyId($propertyId) ?? [] : [];
        $valueIds = array_keys($values);

        $this->getView()->set('property', $propertyId ? $propertiesMapper->getPropertyById($propertyId) : null)
            ->set('translations', $propertyId ? $propertyTranslationMapper->getTranslationsByPropertyId($propertyId) ?? [new PropertytranslationModel()] : [new PropertytranslationModel()])
            ->set('values', $propertyId ? $propertyValueMapper->getValuesByPropertyId($propertyId) ?? [] : [])
            ->set('valueTranslations', $valueIds ? $propertyValuesTranslationsMapper->getTranslations(['value_id' => $valueIds]) ?? [] : [])
            ->set('localeList', $this->getTranslator()->getLocaleList())
            ->set('propertyValues', []);
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $propertyVariantsMapper = new PropertyvariantsMapper();

            if (!$propertyVariantsMapper->exists(['property_id' => $this->getRequest()->getParam('id')])) {
                $propertiesMapper = new PropertiesMapper();
                $propertiesMapper->deletePropertyById($this->getRequest()->getParam('id'));
                $this->addMessage('deleteSuccess');
            } else {
                $this->addMessage('propertyInUse', 'danger');
            }
        }

        $this->redirect(['action' => 'index']);
    }

    public function updateenabledAction()
    {
        if ($this->getRequest()->isSecure()) {
            if (!$this->getRequest()->getParam('enabled')) {
                // This is disabling a property. Therefore items using this property need to be disabled as well.
                $propertyVariantsMapper = new PropertyvariantsMapper();
                $propertyVariants = $propertyVariantsMapper->getPropertiesVariants(['property_id' => $this->getRequest()->getParam('id')]);

                if ($propertyVariants) {
                    $itemIds = [];
                    foreach ($propertyVariants as $propertyVariant) {
                        $itemIds[] = $propertyVariant->getItemVariantId();
                    }

                    $itemsMapper = new ItemsMapper();
                    $itemsMapper->changeStatus($itemIds, false);
                    $this->addMessage('propertyDisabledItemsDisabled', 'info');
                }
            }

            $propertiesMapper = new PropertiesMapper();
            $propertiesMapper->updateEnabled($this->getRequest()->getParam('id'), $this->getRequest()->getParam('enabled') ?? false);
            $this->addMessage('saveSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
