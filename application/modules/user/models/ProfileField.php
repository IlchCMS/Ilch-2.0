<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

use Ilch\Model;

class ProfileField extends Model
{
    /**
     * The id of the profile-field.
     *
     * @var int|null
     */
    protected ?int $id = null;

    /**
     * The key of the profile-field.
     *
     * @var string
     */
    protected string $key = '';

    /**
     * The type of the profile-field.
     *
     * @var int|null
     */
    protected ?int $type = null;

    /**
     * The icon of the profile-field.
     *
     * @var string
     */
    protected string $icon = '';

    /**
     * The addition of the profile-field.
     *
     * @var string
     */
    protected string $addition = '';

    /**
     * The options of the profile-field.
     *
     * @var string
     */
    protected string $options = '';

    /**
     * The show status of the profile-field.
     *
     * @var int
     */
    protected int $show = 1;

    /**
     * The hidden status of the profile-field.
     *
     * @var int
     */
    protected int $hidden = 0;

    /**
     * Registration flag to mark a profile field as to be shown on registration and optionally be required.
     *
     * @var int
     */
    protected int $registration = 0;

    /**
     * Determines if a profile field is private or public.
     *
     * @var int
     */
    protected int $private = 0;

    /**
     * The position of the profile-field.
     *
     * @var int
     */
    protected int $position;

    /**
     * Returns the id of the profile-field.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     * @return ProfileField
     */
    public function setId(int $id): ProfileField
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the key of the profile-field.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Sets the key.
     *
     * @param string $key
     * @return ProfileField
     */
    public function setKey(string $key): ProfileField
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Returns the type of the profile-field.
     *
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param int $type
     * @return ProfileField
     */
    public function setType(int $type): ProfileField
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Returns the icon of the profile-field.
     *
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Sets the icon.
     *
     * @param string $icon
     * @return ProfileField
     */
    public function setIcon(string $icon): ProfileField
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Returns the addition of the profile-field.
     *
     * @return string
     */
    public function getAddition(): string
    {
        return $this->addition;
    }

    /**
     * Sets the addition.
     *
     * @param string $addition
     * @return ProfileField
     */
    public function setAddition(string $addition): ProfileField
    {
        $this->addition = $addition;

        return $this;
    }

    /**
     * Returns the options of the profile-field.
     *
     * @return string|null
     * @since 2.1.50
     */
    public function getOptions(): ?string
    {
        return $this->options;
    }

    /**
     * Sets the options.
     *
     * @param string $options
     * @return ProfileField
     * @since 2.1.50
     */
    public function setOptions(string $options): ProfileField
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Returns the show status of the profile-field.
     *
     * @return int
     */
    public function getShow(): int
    {
        return $this->show;
    }

    /**
     * Sets the show status.
     *
     * @param int $show
     * @return ProfileField
     */
    public function setShow(int $show): ProfileField
    {
        $this->show = $show;

        return $this;
    }

    /**
     * Returns the hidden status of the profile-field.
     *
     * @return int
     */
    public function getHidden(): int
    {
        return $this->hidden;
    }

    /**
     * Sets the hidden status.
     *
     * @param int $hidden
     * @return ProfileField
     */
    public function setHidden(int $hidden): ProfileField
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get value of registration.
     * 0: disabled, 1: enabled, 2: enabled and required
     *
     * @return int
     */
    public function getRegistration(): int
    {
        return $this->registration;
    }

    /**
     * Set value of registration.
     *
     * @param int $registration 0: disabled, 1: enabled, 2: enabled and required
     * @return ProfileField
     */
    public function setRegistration(int $registration): ProfileField
    {
        $this->registration = $registration;
        return $this;
    }

    /**
     * Get the value to determine if this profile field is private or public.
     *
     * @return int
     */
    public function getPrivate(): int
    {
        return $this->private;
    }

    /**
     * Set the value to mark this profile field as private or public.
     *
     * @param int $private
     * @return $this
     */
    public function setPrivate(int $private): ProfileField
    {
        $this->private = $private;
        return $this;
    }

    /**
     * Returns the position of the profile-field.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Sets the position.
     *
     * @param int $position
     * @return ProfileField
     */
    public function setPosition(int $position): ProfileField
    {
        $this->position = $position;

        return $this;
    }
}
