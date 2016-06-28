<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class ProfileFieldContent extends \Ilch\Model
{
    /**
     * The user-id of the ProfileFieldContent.
     *
     * @var int
     */
    protected $userId;

    /**
     * The field-id of the ProfileFieldContent.
     *
     * @var int
     */
    protected $fieldId;

    /**
     * The value of the ProfileFieldContent.
     *
     * @var string
     */
    protected $value;

    /**
     * Returns the user-id of the ProfileFieldContent.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the user-id.
     *
     * @param int $userId
     * @return ProfileFieldContent
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Returns the field-id of the ProfileFieldContent.
     *
     * @return int
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * Sets the field-id.
     *
     * @param int $fieldId
     * @return ProfileFieldContent
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = (int)$fieldId;

        return $this;
    }

    /**
     * Returns the value of the ProfileFieldContent.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value.
     *
     * @param string $value
     * @return ProfileFieldContent
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
