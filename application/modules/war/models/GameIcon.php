<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Models;

use Ilch\Model;

class GameIcon extends Model
{
    protected int $id = 0;
    protected string $title = '';
    protected string $icon = '';

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

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): GameIcon
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): GameIcon
    {
        $this->title = $title;

        return $this;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): GameIcon
    {
        $this->icon = $icon;

        return $this;
    }

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
