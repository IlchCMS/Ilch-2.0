<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Propertyvariant as PropertyVariantModel;

/**
 * Mapper to keep track of all the possible product variants.
 * Example:
 * t-shirt, size: L, color: black
 * t-shirt, size: M, color: red
 *
 * @since 1.4.0
 */
class Propertyvariants extends Mapper
{
    /**
     * Gets the variants.
     *
     * @param array $where
     * @return PropertyVariantModel[]|array
     */
    public function getPropertiesVariants(array $where = []): array
    {
        $propertiesVariantsArray = $this->db()->select('*')
            ->from('shop_properties_variants')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($propertiesVariantsArray)) {
            return [];
        }

        $propertiesVariants = [];

        foreach ($propertiesVariantsArray as $propertyVariant) {
            $propertyVariantModel = new PropertyVariantModel();
            $propertyVariantModel->setId($propertyVariant['id']);
            $propertyVariantModel->setItemId($propertyVariant['item_id']);
            $propertyVariantModel->setItemVariantId($propertyVariant['item_variant_id']);
            $propertyVariantModel->setPropertyId($propertyVariant['property_id']);
            $propertyVariantModel->setValueId($propertyVariant['value_id']);
            $propertiesVariants[$propertyVariant['id']] = $propertyVariantModel;
        }

        return $propertiesVariants;
    }

    /**
     * Check if an entry exists.
     *
     * @param array $where
     * @return bool
     */
    public function exists(array $where = []): bool
    {
        return (bool)$this->db()->select('id')
            ->from('shop_properties_variants')
            ->where($where)
            ->execute()
            ->fetchCell();
    }

    /**
     * Insert or update a variant.
     *
     * @param PropertyVariantModel $model
     * @return int|null
     */
    public function save(PropertyVariantModel $model): ?int
    {
        if ($model->getId()) {
            $this->db()->update('shop_properties_variants')
                ->values(['id' => $model->getId(), 'item_id' => $model->getItemId(), 'item_variant_id' => $model->getItemVariantId(), 'property_id' => $model->getPropertyId(), 'value_id' => $model->getValueId()])
                ->where(['id' => $model->getId()])
                ->execute();
            return $model->getId();
        } else {
            return $this->db()->insert('shop_properties_variants')
                ->values(['item_id' => $model->getItemId(), 'item_variant_id' => $model->getItemVariantId(), 'property_id' => $model->getPropertyId(), 'value_id' => $model->getValueId()])
                ->execute();
        }
    }
}
