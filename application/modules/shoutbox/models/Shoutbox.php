<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Models;

class Shoutbox extends \Ilch\Model
{
    /**
     * The id of the shoutbox.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The uid of the shoutbox.
     *
     * @var int
     */
    protected $uid = 0;

    /**
     * The name of the shoutbox.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The textarea of the shoutbox.
     *
     * @var string
     */
    protected $textarea = '';

    /**
     * The time of the shoutbox.
     *
     * @var string
     */
    protected $time = '';

    /**
     * @param array $entries
     * @return $this
     *  @since 1.5.0
     */
    public function setByArray(array $entries): Shoutbox
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['user_id'])) {
            $this->setUid($entries['user_id']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['textarea'])) {
            $this->setTextarea($entries['textarea']);
        }
        if (isset($entries['time'])) {
            $this->setTime($entries['time']);
        }
        return $this;
    }

    /**
     * Gets the id of the shoutbox.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the shoutbox.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Shoutbox
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the uid of the shoutbox.
     *
     * @return int
     */
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * Sets the uid of the shoutbox.
     *
     * @param int $uid
     * @return $this
     */
    public function setUid(int $uid): Shoutbox
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Gets the name of the shoutbox.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the shoutbox.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Shoutbox
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the textarea of the shoutbox.
     *
     * @return string
     */
    public function getTextarea(): string
    {
        return $this->textarea;
    }

    /**
     * Sets the textarea of the shoutbox.
     *
     * @param string $textarea
     * @return $this
     */
    public function setTextarea(string $textarea): Shoutbox
    {
        $this->textarea = $textarea;

        return $this;
    }

    /**
     * Gets the time of the shoutbox.
     *
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }

    /**
     * Sets the time of the shoutbox.
     *
     * @param string $time
     * @return $this
     */
    public function setTime(string $time): Shoutbox
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.5.0
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'user_id' => $this->getUid(),
                'name' => $this->getName(),
                'textarea' => $this->getTextarea(),
                'time' => $this->getTime(),
            ]
        );
    }
}
