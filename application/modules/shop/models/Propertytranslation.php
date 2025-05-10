<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

/**
 * The model for a property translation.
 *
 * @since 1.4.0
 */
class Propertytranslation extends Model
{
    /**
     * The id of the property translation.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The property id.
     *
     * @var int|null
     */
    protected ?int $property_id = null;

    /**
     * The locale of this translation.
     *
     * @var string
     */
    protected string $locale = '';

    /**
     * The text of this translation.
     *
     * @var string
     */
    protected string $text = '';

    /**
     * Gets the id of the translation.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the translation.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Gets the property id.
     *
     * @return int|null
     */
    public function getPropertyId(): ?int
    {
        return $this->property_id;
    }

    /**
     * Sets the property id.
     *
     * @param int|null $property_id
     * @return $this
     */
    public function setPropertyId(?int $property_id): Propertytranslation
    {
        $this->property_id = $property_id;
        return $this;
    }

    /**
     * Gets the locale of this translation.
     * Example: de_DE
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Sets the locale of this translation.
     *
     * @param string $locale Example: de_DE
     * @return $this
     */
    public function setLocale(string $locale): Propertytranslation
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Gets the text of the translation.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the translation.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Propertytranslation
    {
        $this->text = $text;
        return $this;
    }
}
