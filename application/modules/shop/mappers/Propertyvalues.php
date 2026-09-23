<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Propertyvalue as PropertyvalueModel;

/**
 * Mapper for possible values of a property.
 * Example: Property "material" with the values "metal" and "wood".
 *
 * @since 1.4.0
 */
class Propertyvalues extends Mapper
{
    /**
     * Gets the values.
     *
     * @param array $where
     * @return PropertyvalueModel[]|array
     */
    public function getValues(array $where = []): array
    {
        $valuesArray = $this->db()->select('*')
            ->from('shop_properties_values')
            ->where($where)
            ->order(['property_id' => 'ASC', 'position' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($valuesArray)) {
            return [];
        }

        $values = [];

        foreach ($valuesArray as $value) {
            $propertyValueModel = new PropertyvalueModel();
            $propertyValueModel->setId($value['id']);
            $propertyValueModel->setPropertyId($value['property_id']);
            $propertyValueModel->setPosition($value['position']);
            $propertyValueModel->setValue($value['value']);
            $values[$value['id']] = $propertyValueModel;
        }

        return $values;
    }

    /**
     * Gets the values by id.
     *
     * @param int $propertyId
     * @return PropertyvalueModel[]|null
     */
    public function getValuesByPropertyId(int $propertyId): ?array
    {
        $property = $this->getValues(['property_id' => $propertyId]);

        if (empty($property)) {
            return null;
        }

        return $property;
    }

    /**
     * Insert or update values.
     *
     * @param PropertyvalueModel $model
     * @return int ID of the saved value.
     */
    public function save(PropertyvalueModel $model): int
    {
        if ($model->getId()) {
            $this->db()->update('shop_properties_values')
                ->values(['property_id' => $model->getPropertyId(), 'position' => $model->getPosition(), 'value' => $model->getValue()])
                ->where(['id' => $model->getId()])
                ->execute();

            return $model->getId();
        }

        return $this->db()->insert('shop_properties_values')
            ->values(['property_id' => $model->getPropertyId(), 'position' => $model->getPosition(), 'value' => $model->getValue()])
            ->execute();
    }

    /**
     * Deletes the value by id.
     *
     * @param int $id
     * @return bool
     */
    public function deleteValueById(int $id): bool
    {
        return (bool) $this->db()->delete('shop_properties_values')
            ->where(['id' => $id])
            ->execute();
    }
}
