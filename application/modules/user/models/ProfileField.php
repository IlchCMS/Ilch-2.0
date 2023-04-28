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
     * The options of the profile-field.
     *
     * @var string
     */
    protected $options;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     * @return ProfileField
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Returns the key of the profile-field.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Sets the key.
     *
     * @param string $key
     * @return ProfileField
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Returns the type of the profile-field.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param int $type
     * @return ProfileField
     */
    public function setType($type)
    {
        $this->type = (int)$type;

        return $this;
    }

    /**
     * Returns the icon of the profile-field.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets the icon.
     *
     * @param string $icon
     * @return ProfileField
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Returns the addition of the profile-field.
     *
     * @return string
     */
    public function getAddition()
    {
        return $this->addition;
    }

    /**
     * Sets the addition.
     *
     * @param string $addition
     * @return ProfileField
     */
    public function setAddition($addition)
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
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Sets the show status.
     *
     * @param int $show
     * @return ProfileField
     */
    public function setShow($show)
    {
        $this->show = (int)$show;

        return $this;
    }

    /**
     * Returns the hidden status of the profile-field.
     *
     * @return int
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Sets the hidden status.
     *
     * @param int $hidden
     * @return ProfileField
     */
    public function setHidden($hidden)
    {
        $this->hidden = (int)$hidden;

        return $this;
    }

    /**
     * Returns the position of the profile-field.
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets the position.
     *
     * @param int $position
     * @return ProfileField
     */
    public function setPosition($position)
    {
        $this->position = (int)$position;

        return $this;
    }
}
