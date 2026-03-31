<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Models;

use Ilch\Model;

class GameIcon extends Model
{
    /**
     * @var int|null
     */
    protected ?int $id = null;
    /**
     * @var string
     */
    protected string $title = '';
    /**
     * @var string
     */
    protected string $icon = '';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): GameIcon
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['icon'])) {
            $this->setIcon($entries['icon']);
        }

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return $this
     */
    public function setId(?int $id): GameIcon
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): GameIcon
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon): GameIcon
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'title' => $this->getTitle(),
                'icon'  => $this->getIcon(),
            ]
        );
    }
}
