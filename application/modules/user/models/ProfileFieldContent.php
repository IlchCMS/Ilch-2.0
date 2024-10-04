<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

class ProfileFieldContent extends \Ilch\Model
{
    /**
     * The field-id of the ProfileFieldContent.
     *
     * @var int
     */
    protected int $fieldId;

    /**
     * The user-id of the ProfileFieldContent.
     *
     * @var int
     */
    protected int $userId;

    /**
     * The value of the ProfileFieldContent.
     *
     * @var string
     */
    protected string $value;

    /**
     * Returns the field-id of the ProfileFieldContent.
     *
     * @return int
     */
    public function getFieldId(): int
    {
        return $this->fieldId;
    }

    /**
     * Sets the field-id.
     *
     * @param int $fieldId
     * @return ProfileFieldContent
     */
    public function setFieldId(int $fieldId): ProfileFieldContent
    {
        $this->fieldId = $fieldId;

        return $this;
    }

    /**
     * Returns the user-id of the ProfileFieldContent.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the user-id.
     *
     * @param int $userId
     * @return ProfileFieldContent
     */
    public function setUserId(int $userId): ProfileFieldContent
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Returns the value of the ProfileFieldContent.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Sets the value.
     *
     * @param string $value
     * @return ProfileFieldContent
     */
    public function setValue(string $value): ProfileFieldContent
    {
        $this->value = $value;

        return $this;
    }
}
