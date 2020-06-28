<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

class ProfileField extends \Ilch\Model
{
    /**
     * The id of the profile-field.
     *
     * @var int
     */
    protected $id;

    /**
     * The key of the profile-field.
     *
     * @var string
     */
    protected $key;

    /**
     * The type of the profile-field.
     *
     * @var int
     */
    protected $type;

    /**
     * The icon of the profile-field.
     *
     * @var string
     */
    protected $icon;

    /**
     * The addition of the profile-field.
     *
     * @var string
     */
    protected $addition;

    /**
     * The show status of the profile-field.
     *
     * @var int
     */
    protected $show;

    /**
     * The hidden status of the profile-field.
     *
     * @var int
     */
    protected $hidden;

    /**
     * The position of the profile-field.
     *
     * @var int
     */
    protected $position;

    /**
     * Returns the id of the profile-field.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     * @return ProfileField
     */
    public function setId($id): ProfileField
    {
        $this->id = (int)$id;

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
    public function setKey($key): ProfileField
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Returns the type of the profile-field.
     *
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param int $type
     * @return ProfileField
     */
    public function setType($type): ProfileField
    {
        $this->type = (int)$type;

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
    public function setIcon($icon): ProfileField
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
    public function setAddition($addition): ProfileField
    {
        $this->addition = $addition;

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
    public function setShow($show): ProfileField
    {
        $this->show = (int)$show;

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
    public function setHidden($hidden): ProfileField
    {
        $this->hidden = (int)$hidden;

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
    public function setPosition($position): ProfileField
    {
        $this->position = (int)$position;

        return $this;
    }
}
