<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\ProfileField as ProfileFieldModel;

class ProfileFields extends \Ilch\Mapper
{
    public function getProfileFields()
    {
        $profileFieldRows = $this->db()->select('*')
            ->from('profile_fields')
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

    public function save(ProfileFieldModel $profileField)
    {
        $fields = [];
        $name = $profileField->getName();

        if (!empty($name)) {
            $fields['name'] = $profileField->getName();
            $fields['type'] = $profileField->getType();
        }

        $profileFieldId = (int) $this->db()->select('id')
            ->from('profile_fields')
            ->where(['id' => $profileField->getId()])
            ->execute()
            ->fetchCell();

        if ($profileFieldId) {
            /*
             * ProfileField does exist already, update.
             */
            $this->db()->update('profile_fields')
                ->values($fields)
                ->where(['id' => $profileFieldId])
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

    public function deleteProfileField($id)
    {
        $this->db()->delete('profile_fields')
            ->where(['id' => $id])
            ->execute();
    }

    public function profileFieldWithIdExists($id)
    {
        return (boolean) $this->db()->select('COUNT(*)', 'profile_fields', ['id' => (int)$id])
            ->execute()
            ->fetchCell();
    }

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

        return $profileField;
    }
}
