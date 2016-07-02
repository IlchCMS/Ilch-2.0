<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\ProfileField as ProfileFieldModel;

class ProfileFields extends \Ilch\Mapper
{
    /**
     * Returns all profile-fields.
     *
     * @return array()|\Modules\User\Models\ProfileField
     */
    public function getProfileFields()
    {
        $profileFieldRows = $this->db()->select('*')
            ->from('profile_fields')
            ->order(['position' => 'ASC'])
            ->execute()
            ->fetchRows();

        $profileFields = [];
        if (!empty($profileFieldRows)) {
            foreach ($profileFieldRows as $profileFieldRow) {
                $profileField = $this->loadFromArray($profileFieldRow);
                $profileFields[] = $profileField;
            }
        }
        return $profileFields;
    }

    /**
     * Returns a ProfileField model found by the id.
     *
     * @param  int $id
     * @return null|\Modules\User\Models\ProfileField
     */
    public function getProfileFieldById($id)
    {
        $profileFieldRow = $this->db()->select('*')
            ->from('profile_fields')
            ->where(['id' => $id])
            ->execute()
            ->fetchRows();

        if (!empty($profileFieldRow)) {
            $profileFields = array_map([$this, 'loadFromArray'], $profileFieldRow);
            return reset($profileFields);
        }
        return null;
    }

    /**
     * Updates the position of a profile-field in the database.
     *
     * @param int $id, int $position
     *
     */
    public function updatePositionById($id, $position) {
        $this->db()->update('profile_fields')
            ->values(['position' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates a ProfileField model in the database.
     *
     * @param UserModel $user
     *
     * @return int The id of the updated or inserted profile-field.
     */
    public function save(ProfileFieldModel $profileField)
    {
        $fields = [];
        $name = $profileField->getName();

        if (!empty($name)) {
            $fields['name'] = $profileField->getName();
            $fields['type'] = $profileField->getType();
        }

        $id = (int) $this->db()->select('id')
            ->from('profile_fields')
            ->where(['id' => $profileField->getId()])
            ->execute()
            ->fetchCell();

        if ($id) {
            /*
             * ProfileField does exist already, update.
             */
            $this->db()->update('profile_fields')
                ->values($fields)
                ->where(['id' => $id])
                ->execute();
        } else {
            /*
             * ProfileField does not exist yet, insert.
             */
            $id = $this->db()->insert('profile_fields')
                ->values($fields)
                ->execute();
        }

        return $id;
    }

    /**
     * Deletes a given profile-field with the given id.
     *
     * @param  int $id
     *
     * @return boolean True if success, otherwise false.
     */
    public function deleteProfileField($id)
    {
        return $this->db()->delete('profile_fields')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Returns whether a profile-field exists.
     *
     * @param  int $id
     *
     * @return boolean True if a profile-field with this id exists, false otherwise.
     */
    public function profileFieldWithIdExists($id)
    {
        return (boolean) $this->db()->select('COUNT(*)', 'profile_fields', ['id' => (int)$id])
            ->execute()
            ->fetchCell();
    }

    /**
     * Returns a profile-field created using an array with data.
     *
     * @param  mixed[] $profileFieldRow
     * @return ProfileFieldModel
     */
    public function loadFromArray($profileFieldRow = [])
    {
        $profileField = new ProfileFieldModel();

        if (isset($profileFieldRow['id'])) {
            $profileField->setId($profileFieldRow['id']);
        }

        if (isset($profileFieldRow['name'])) {
            $profileField->setName($profileFieldRow['name']);
        }

        if (isset($profileFieldRow['type'])) {
            $profileField->setType($profileFieldRow['type']);
        }

        if (isset($profileFieldRow['position'])) {
            $profileField->setPosition($profileFieldRow['position']);
        }

        return $profileField;
    }
}
