<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\ProfileFieldTranslation as ProfileFieldTranslationModel;

class ProfileFieldsTranslation extends \Ilch\Mapper
{
    /**
     * Returns all ProfileFieldTranslation found by the locale.
     *
     * @param  string $locale
     * @return array()|\Modules\User\Models\ProfileFieldTranslation
     */
    public function getProfileFieldTranslationByLocale($locale)
    {
        $profileFieldTranslationRows = $this->db()->select('*')
            ->from('profile_trans')
            ->where(['locale' => $locale])
            ->execute()
            ->fetchRows();

        $profileFieldsTranslation = [];
        if (!empty($profileFieldTranslationRows)) {
            foreach ($profileFieldTranslationRows as $profileFieldTranslationRow) {
                $profileFieldTranslation = $this->loadFromArray($profileFieldTranslationRow);
                $profileFieldsTranslation[] = $profileFieldTranslation;
            }
        }
        return $profileFieldsTranslation;
    }

    /**
     * Returns a ProfileFieldTranslation model found by the fieldid.
     *
     * @param  int $fieldId
     * @return array()|\Modules\User\Models\ProfileFieldTranslation
     */
    public function getProfileFieldTranslationByFieldId($fieldId)
    {
        $profileFieldTranslationRows = $this->db()->select('*')
            ->from('profile_trans')
            ->where(['field_id' => $fieldId])
            ->execute()
            ->fetchRows();

        $profileFieldsTranslation = [];
        if (!empty($profileFieldTranslationRows)) {
            foreach ($profileFieldTranslationRows as $profileFieldTranslationRow) {
                $profileFieldTranslation = $this->loadFromArray($profileFieldTranslationRow);
                $profileFieldsTranslation[] = $profileFieldTranslation;
            }
        }
        return $profileFieldsTranslation;
    }

    /**
     * Returns a ProfileFieldTranslationModel created using an array with data.
     *
     * @param array $profileFieldRow
     * @return ProfileFieldTranslationModel
     */
    public function loadFromArray($profileFieldRow = [])
    {
        $profileFieldTranslation = new ProfileFieldTranslationModel();

        if (isset($profileFieldRow['field_id'])) {
            $profileFieldTranslation->setFieldId($profileFieldRow['field_id']);
        }

        if (isset($profileFieldRow['locale'])) {
            $profileFieldTranslation->setLocale($profileFieldRow['locale']);
        }

        if (isset($profileFieldRow['name'])) {
            $profileFieldTranslation->setName($profileFieldRow['name']);
        }

        return $profileFieldTranslation;
    }

    /**
     * Deletes the profilefield-translations with a given fieldid.
     * Use this function when a profilefield got deleted and it's
     * translations are no longer needed.
     *
     * @param  int $fieldId
     *
     * @return bool True if success, otherwise false.
     */
    public function deleteProfileFieldTranslationsByFieldId($fieldId)
    {
        return $this->db()->delete('profile_trans')
            ->where(['field_id' => $fieldId])
            ->execute();
    }

    /**
     * Deletes the profilefield-translation with a given locale and fieldid.
     *
     * @param  string $locale
     * @param  int $fieldId
     *
     * @return bool True if success, otherwise false.
     */
    public function deleteProfileFieldTranslation($locale, $fieldId)
    {
        return $this->db()->delete('profile_trans')
            ->where(['locale' => $locale, 'field_id' => $fieldId])
            ->execute();
    }

    /**
     * Inserts or updates a ProfileFieldTranslation model in the database.
     *
     * @param ProfileFieldTranslationModel $profileFieldTranslation
     */
    public function save(ProfileFieldTranslationModel $profileFieldTranslation)
    {
        $fields = [];
        $fieldId = $profileFieldTranslation->getFieldId();

        if (!empty($fieldId)) {
            $fields['field_id'] = $profileFieldTranslation->getFieldId();
            $fields['locale'] = $profileFieldTranslation->getLocale();
            $fields['name'] = $profileFieldTranslation->getName();
        }

        $fieldId = (int) $this->db()->select('field_id')
            ->from('profile_trans')
            ->where(['field_id' => $fieldId, 'locale' => $profileFieldTranslation->getLocale()])
            ->execute()
            ->fetchCell();

        if ($fieldId) {
            /*
             * profileFieldTranslation does exist already, update.
             */
            $this->db()->update('profile_trans')
                ->values($fields)
                ->where(['field_id' => $fieldId, 'locale' => $profileFieldTranslation->getLocale()])
                ->execute();
        } else {
            /*
             * profileFieldTranslation does not exist yet, insert.
             */
            $this->db()->insert('profile_trans')
                ->values($fields)
                ->execute();
        }
    }
}
