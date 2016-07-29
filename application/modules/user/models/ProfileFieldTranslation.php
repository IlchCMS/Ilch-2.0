<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class ProfileFieldTranslation extends \Ilch\Model
{
    /**
     * The field-id of the ProfileFieldTranslation.
     *
     * @var int
     */
    protected $fieldId;

    /**
     * The locale of the ProfileFieldTranslation.
     *
     * @var string
     */
    protected $locale;

    /**
     * The name of the ProfileFieldTranslation.
     *
     * @var string
     */
    protected $name;

    /**
     * Returns the field-id of the ProfileFieldTranslation.
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
     * @return ProfileFieldTranslation
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = (int)$fieldId;

        return $this;
    }

    /**
     * Returns the locale of the ProfileFieldTranslation.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the locale.
     *
     * @param string $locale
     * @return ProfileFieldTranslation
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Returns the name of the ProfileFieldTranslation.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     * @return ProfileFieldTranslation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
