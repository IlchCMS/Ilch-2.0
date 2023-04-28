<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\ProfileField as ProfileFieldModel;

class ProfileFields extends \Ilch\Mapper
{
    /**
     * Returns all profile-fields.
     *
     * @param  array $where
     * @return array()|\Modules\User\Models\ProfileField
     */
    public function getProfileFields($where = [])
    {
        $profileFieldRows = $this->db()->select('*')
            ->from('profile_fields')
            ->where($where, 'or')
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
     * Returns a ProfileField model found by the key.
     *
     * @param  int $key
     * @return null|\Modules\User\Models\ProfileField
     */
    public function getProfileFieldIdByKey($key)
    {
        $profileFieldRow = $this->db()->select('*')
            ->from('profile_fields')
            ->where(['key' => $key])
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
     * @param int $id
     * @param int $position
     *
     */
    public function updatePositionById($id, $position)
    {
        $this->db()->update('profile_fields')
            ->values(['position' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates a ProfileField model in the database.
     *
     * @param ProfileFieldModel $profileField
     *
     * @return int The id of the updated or inserted profile-field.
     */
    public function save(ProfileFieldModel $profileField)
    {
        $fields = [];
        $key = $profileField->getKey();

        if (!empty($key)) {
            $fields['key'] = $profileField->getKey();
            $fields['type'] = $profileField->getType();
            $fields['icon'] = $profileField->getIcon();
            $fields['addition'] = $profileField->getAddition();
            $fields['options'] = $profileField->getOptions();
            $fields['show'] = $profileField->getShow();
            $fields['position'] = $profileField->getPosition();
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
     * Updates profile-field with given id.
     *
     * @param integer $id
     */
    public function update($id)
    {
        $show = (int) $this->db()->select('show')
            ->from('profile_fields')
            ->where(['id' => $id])
            ->execute()
            ->fetchCell();

        if ($show == 1) {
            $this->db()->update('profile_fields')
                ->values(['show' => 0])
                ->where(['id' => $id])
                ->execute();
        } else {
            $this->db()->update('profile_fields')
                ->values(['show' => 1])
                ->where(['id' => $id])
                ->execute();
        }
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
     * Returns the count of profile-fields.
     *
     *
     * @return int The count of profile-fields.
     */
    public function getCountOfProfileFields()
    {
        return $this->db()->select('*')
            ->from('profile_fields')
            ->execute()
            ->getNumRows();
    }

    /**
     * Returns a profile-field created using an array with data.
     *
     * @param array $profileFieldRow
     * @return ProfileFieldModel
     */
    public function loadFromArray($profileFieldRow = [])
    {
        $profileField = new ProfileFieldModel();

        if (isset($profileFieldRow['id'])) {
            $profileField->setId($profileFieldRow['id']);
        }

        if (isset($profileFieldRow['key'])) {
            $profileField->setKey($profileFieldRow['key']);
        }

        if (isset($profileFieldRow['type'])) {
            $profileField->setType($profileFieldRow['type']);
        }

        if (isset($profileFieldRow['icon'])) {
            $profileField->setIcon($profileFieldRow['icon']);
        }

        if (isset($profileFieldRow['addition'])) {
            $profileField->setAddition($profileFieldRow['addition']);
        }

        if (isset($profileFieldRow['options'])) {
            $profileField->setOptions($profileFieldRow['options']);
        }

        if (isset($profileFieldRow['show'])) {
            $profileField->setShow($profileFieldRow['show']);
        }

        if (isset($profileFieldRow['hidden'])) {
            $profileField->setHidden($profileFieldRow['hidden']);
        }

        if (isset($profileFieldRow['position'])) {
            $profileField->setPosition($profileFieldRow['position']);
        }

        return $profileField;
    }
}
