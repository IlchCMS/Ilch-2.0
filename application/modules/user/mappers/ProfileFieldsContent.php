<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\ProfileFieldContent as ProfileFieldContentModel;

class ProfileFieldsContent extends \Ilch\Mapper
{
    /**
     * Returns a ProfileFieldContent model found by the userid.
     *
     * @param  int $userId
     * @return array()|\Modules\User\Models\ProfileFieldContent
     */
    public function getProfileFieldContentByUserId($userId)
    {
        $profileFieldContentRows = $this->db()->select('*')
            ->from('profile_content')
            ->where(['user_id' => $userId])
            ->execute()
            ->fetchRows();

        $profileFieldsContent = [];
        if (!empty($profileFieldContentRows)) {
            foreach ($profileFieldContentRows as $profileFieldContentRow) {
                $profileFieldContent = $this->loadFromArray($profileFieldContentRow);
                $profileFieldsContent[] = $profileFieldContent;
            }
        }
        return $profileFieldsContent;
    }

    /**
     * Returns a ProfileFieldContentModel created using an array with data.
     *
     * @param array $profileFieldRow
     * @return ProfileFieldContentModel
     */
    public function loadFromArray($profileFieldRow = [])
    {
        $profileFieldContent = new ProfileFieldContentModel();

        if (isset($profileFieldRow['user_id'])) {
            $profileFieldContent->setUserId($profileFieldRow['user_id']);
        }

        if (isset($profileFieldRow['field_id'])) {
            $profileFieldContent->setFieldId($profileFieldRow['field_id']);
        }

        if (isset($profileFieldRow['value'])) {
            $profileFieldContent->setValue($profileFieldRow['value']);
        }

        return $profileFieldContent;
    }

    /**
     * Deletes the profile-content for a user with the given id.
     *
     * @param  int $userId
     * @return boolean True if success, otherwise false.
     */
    public function deleteProfileFieldContentByUserId($userId)
    {
        return $this->db()->delete('profile_content')
            ->where(['user_id' => $userId])
            ->execute();
    }

    /**
     * Deletes the profile-content with a given field-id.
     *
     * @param  int $fieldId
     * @return boolean True if success, otherwise false.
     */
    public function deleteProfileFieldContentByFieldId($fieldId)
    {
        return $this->db()->delete('profile_content')
            ->where(['field_id' => $fieldId])
            ->execute();
    }

    /**
     * Delete the value of a specific profile field for a specific user.
     *
     * @param $userId
     * @param $fieldId
     * @return \Ilch\Database\Mysql\Result|int
     * @since 2.1.32
     */
    public function deleteProfileFieldContentByUserAndFieldId($userId, $fieldId)
    {
        return $this->db()->delete('profile_content')
            ->where(['user_id' => $userId, 'field_id' => $fieldId])
            ->execute();
    }

    /**
     * Inserts or updates a ProfileFieldContent model in the database.
     *
     * @param ProfileFieldContentModel $profileFieldContent
     */
    public function save(ProfileFieldContentModel $profileFieldContent)
    {
        $fields = [];
        $userId = $profileFieldContent->getUserId();

        if (!empty($userId)) {
            $fields['user_id'] = $profileFieldContent->getUserId();
            $fields['field_id'] = $profileFieldContent->getFieldId();
            $fields['value'] = $profileFieldContent->getValue();
        }

        $fieldId = (int) $this->db()->select('field_id')
            ->from('profile_content')
            ->where(['user_id' => $userId, 'field_id' => $profileFieldContent->getFieldId()])
            ->execute()
            ->fetchCell();

        if ($fieldId) {
            /*
             * profileFieldContent does exist already, update.
             */
            $this->db()->update('profile_content')
                ->values($fields)
                ->where(['user_id' => $userId, 'field_id' => $fieldId])
                ->execute();
        } else {
            /*
             * profileFieldContent does not exist yet, insert.
             */
            $this->db()->insert('profile_content')
                ->values($fields)
                ->execute();
        }
    }
}
