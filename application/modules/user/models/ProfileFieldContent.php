<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class ProfileFieldContent extends \Ilch\Model
{
    protected $userId;

    protected $fieldId;

    protected $value;

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    public function getFieldId()
    {
        return $this->fieldId;
    }

    public function setFieldId($fieldId)
    {
        $this->fieldId = (int)$fieldId;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
