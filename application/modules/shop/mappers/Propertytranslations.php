<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Modules\Shop\Models\Propertytranslation as PropertyTranslationModel;

/**
 * Mapper for the translations of properties.
 *
 * @since 1.4.0
 */
class Propertytranslations extends Mapper
{
    /**
     * Gets the translations.
     *
     * @param array $where
     * @return PropertyTranslationModel[]|array
     */
    public function getTranslations(array $where = []): array
    {
        $translationsArray = $this->db()->select('*')
            ->from('shop_properties_trans')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($translationsArray)) {
            return [];
        }

        $translations = [];

        foreach ($translationsArray as $property) {
            $propertyTranslationModel = new PropertyTranslationModel();
            $propertyTranslationModel->setId($property['id']);
            $propertyTranslationModel->setPropertyId($property['property_id']);
            $propertyTranslationModel->setLocale($property['locale']);
            $propertyTranslationModel->setText($property['text']);
            $translations[$property['id']] = $propertyTranslationModel;
        }

        return $translations;
    }

    /**
     * Gets the translations by property id.
     *
     * @param int $propertyId
     * @return PropertyTranslationModel[]|null
     */
    public function getTranslationsByPropertyId(int $propertyId): ?array
    {
        $property = $this->getTranslations(['property_id' => $propertyId]);

        if (empty($property)) {
            return null;
        }

        return $property;
    }

    /**
     * @param string $locale
     * @param int[] $propertyIds
     * @return array|null
     */
    public function getTranslationsByLocaleAndPropertyIds(string $locale, array $propertyIds): ?array
    {
        return $this->getTranslations(['locale' => $locale, 'property_id' => $propertyIds]);
    }

    /**
     * Insert or update translations.
     *
     * @param PropertyTranslationModel $model
     */
    public function save(PropertyTranslationModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('shop_properties_trans')
                ->values(['property_id' => $model->getPropertyId(), 'locale' => $model->getLocale(), 'text' => $model->getText()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('shop_properties_trans')
                ->values(['property_id' => $model->getPropertyId(), 'locale' => $model->getLocale(), 'text' => $model->getText()])
                ->execute();
        }
    }

    /**
     * Deletes the translation by id.
     *
     * @param int $id
     * @return Result|int
     */
    public function deleteTranslationById(int $id)
    {
        return $this->db()->delete('shop_properties_trans')
            ->where(['id' => $id])
            ->execute();
    }
}
