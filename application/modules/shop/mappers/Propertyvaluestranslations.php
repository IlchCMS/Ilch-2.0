<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Modules\Shop\Models\Propertyvaluetranslation as PropertyValueTranslationModel;

/**
 * Mapper for the property value translations.
 * Example: Property "material" with the values "metal" and "wood".
 * These values can have translations for multiple locales.
 * "de_DE": "Metall" and "Holz"
 *
 * @since 1.4.0
 */
class Propertyvaluestranslations extends Mapper
{
    /**
     * Gets the translations.
     *
     * @param array $where
     * @return PropertyValueTranslationModel[]|array
     */
    public function getTranslations(array $where = []): ?array
    {
        $translationsArray = $this->db()->select('*')
            ->from('shop_properties_values_trans')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($translationsArray)) {
            return null;
        }

        $translations = [];

        foreach ($translationsArray as $property) {
            $propertyTranslationModel = new PropertyValueTranslationModel();
            $propertyTranslationModel->setId($property['id']);
            $propertyTranslationModel->setValueId($property['value_id']);
            $propertyTranslationModel->setLocale($property['locale']);
            $propertyTranslationModel->setText($property['text']);
            $translations[] = $propertyTranslationModel;
        }

        return $translations;
    }

    /**
     * @param string $locale
     * @param int[] $valueIds
     * @return array|null
     */
    public function getTranslationsByLocaleAndValueIds(string $locale, array $valueIds): ?array
    {
        return $this->getTranslations(['locale' => $locale, 'value_id' => $valueIds]);
    }

    /**
     * Insert or update translations.
     *
     * @param PropertyValueTranslationModel $model
     */
    public function save(PropertyValueTranslationModel $model)
    {
        if ($model->getId()) {
            $this->db()->update('shop_properties_values_trans')
                ->values(['value_id' => $model->getValueId(), 'locale' => $model->getLocale(), 'text' => $model->getText()])
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('shop_properties_values_trans')
                ->values(['value_id' => $model->getValueId(), 'locale' => $model->getLocale(), 'text' => $model->getText()])
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
        return $this->db()->delete('shop_properties_values_trans')
            ->where(['id' => $id])
            ->execute();
    }
}
