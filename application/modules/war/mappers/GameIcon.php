<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\GameIcon as GameIconModel;

class GameIcon extends \Ilch\Mapper
{
    public string $tablename = 'war_game_icon';

    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Returns all game icons ordered by title.
     *
     * @return GameIconModel[]
     */
    public function getGameIcons(): array
    {
        $rows = $this->db()->select()
            ->fields(['id', 'title', 'icon'])
            ->from([$this->tablename])
            ->order(['title' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($rows)) {
            return [];
        }

        return array_map(static function (array $row): GameIconModel {
            return (new GameIconModel())->setByArray($row);
        }, $rows);
    }

    /**
     * Returns a game icon by its ID.
     */
    public function getGameIconById(int $id): ?GameIconModel
    {
        $row = $this->db()->select()
            ->fields(['id', 'title', 'icon'])
            ->from([$this->tablename])
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($row)) {
            return null;
        }

        return (new GameIconModel())->setByArray($row);
    }

    /**
     * Returns a map of title => icon filename for quick lookup in views.
     * Derived from getGameIcons() to avoid duplicate query logic.
     *
     * @return array<string, string>
     */
    public function getGameIconMap(): array
    {
        $map = [];
        foreach ($this->getGameIcons() as $icon) {
            $map[$icon->getTitle()] = $icon->getIcon();
        }
        return $map;
    }

    /**
     * Saves (insert or update) a game icon model.
     *
     * @return int The ID of the saved record.
     */
    public function save(GameIconModel $model): int
    {
        $data = [
            'title' => $model->getTitle(),
            'icon'  => $model->getIcon(),
        ];

        if ($model->getId() > 0) {
            $this->db()->update($this->tablename)
                ->values($data)
                ->where(['id' => $model->getId()])
                ->execute();

            return $model->getId();
        }

        return (int) $this->db()->insert($this->tablename)
            ->values($data)
            ->execute();
    }

    /**
     * Deletes a game icon by its ID.
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
