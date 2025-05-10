<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Modules\Shop\Models\Property as PropertyModel;

/**
 * Mapper for properties that can be used to create product variants.
 * Like a t-shirt in a different size and/or color.
 *
 * @since 1.4.0
 */
class Properties extends Mapper
{
    /**
     * Gets the properties.
     *
     * @param array $where
     * @return PropertyModel[]|array
     */
    public function getProperties(array $where = []): array
    {
        $propertiesArray = $this->db()->select('*')
            ->from('shop_properties')
            ->where($where)
            ->order(['name' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($propertiesArray)) {
            return [];
        }

        $properties = [];

        foreach ($propertiesArray as $property) {
            $propertyModel = new PropertyModel();
            $propertyModel->setId($property['id']);
            $propertyModel->setName($property['name']);
            $propertyModel->setEnabled($property['enabled']);
            $properties[$property['id']] = $propertyModel;
        }

        return $properties;
    }

    /**
     * Gets the property by id.
     *
     * @param int $id
     * @return PropertyModel|null
     */
    public function getPropertyById(int $id): ?PropertyModel
    {
        $property = $this->getProperties(['id' => $id]);

        if (empty($property)) {
            return null;
        }

        return reset($property);
    }

    /**
     * Insert or update property.
     *
     * @param PropertyModel $model
     * @return int|null
     */
    public function save(PropertyModel $model): ?int
    {
        if ($model->getId()) {
            $this->db()->update('shop_properties')
                ->values(['name' => $model->getName(), 'enabled' => $model->isEnabled()])
                ->where(['id' => $model->getId()])
                ->execute();
            return $model->getId();
        } else {
            return $this->db()->insert('shop_properties')
                ->values(['name' => $model->getName(), 'enabled' => $model->isEnabled()])
                ->execute();
        }
    }

    /**
     * Update the value of enabled for the property.
     *
     * @param int $id
     * @param bool $enabled
     * @return void
     */
    public function updateEnabled(int $id, bool $enabled)
    {
        $this->db()->update('shop_properties')
            ->values(['enabled' => $enabled])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes the property by id.
     *
     * @param int $id
     * @return Result|int
     */
    public function deletePropertyById(int $id)
    {
        return $this->db()->delete('shop_properties')
            ->where(['id' => $id])
            ->execute();
    }
}
