<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Models;

use Ilch\Model;

/**
 * The model for a property value translation.
 *
 * @since 1.4.0
 */
class Propertyvaluetranslation extends Model
{
    /**
     * The id of the value translation.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The value id.
     *
     * @var int|null
     */
    protected ?int $value_id = null;

    /**
     * The locale.
     *
     * @var string
     */
    protected string $locale = '';

    /**
     * The text of the translation.
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
     * Get the value id of the translation.
     * This connects the value and the translation for the value.
     *
     * @return int|null
     */
    public function getValueId(): ?int
    {
        return $this->value_id;
    }

    /**
     * Sets the value of of the translation.
     * This connects the value and the translation for the value.
     *
     * @param int|null $value_id
     * @return $this
     */
    public function setValueId(?int $value_id): Propertyvaluetranslation
    {
        $this->value_id = $value_id;
        return $this;
    }

    /**
     * Get the locale of the translation.
     * Example: de_DE
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Set the locale of the translation.
     *
     * @param string $locale Example: de_DE
     * @return $this
     */
    public function setLocale(string $locale): Propertyvaluetranslation
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Get the text of the translation.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Set the text of the translation.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Propertyvaluetranslation
    {
        $this->text = $text;
        return $this;
    }
}
