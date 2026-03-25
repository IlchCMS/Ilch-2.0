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

    /**
     * Returns all game icons.
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

        return array_map(function ($row) {
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
     *
     * @return array<string, string>
     */
    public function getGameIconMap(): array
    {
        $rows = $this->db()->select()
            ->fields(['title', 'icon'])
            ->from([$this->tablename])
            ->execute()
            ->fetchRows();

        $map = [];
        foreach ($rows ?? [] as $row) {
            $map[$row['title']] = $row['icon'];
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
    public function delete(int $id): void
    {
        $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
