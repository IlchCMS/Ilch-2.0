<?php

/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvteam\Mappers;

use Modules\Kvteam\Models\Team as TeamModel;

class Team extends \Ilch\Mapper
{
    /**
     * Gets the Teams.
     *
     * @param array $where
     * @return TeamModel[]
     */
    public function getTeams(array $where = []): array
    {
        $entryArray = $this->db()->select('*')
            ->from('kvteam')
            ->where($where)
            ->order(['position' => 'ASC'])
            ->execute()
            ->fetchRows();

        $teams = [];

        if (empty($entryArray)) {
            return $teams;
        }

        foreach ($entryArray as $entries) {
            $entryModel = new TeamModel();
            $entryModel->setId($entries['id']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setUserIds($entries['userIds']);
            $entryModel->setPosition($entries['position']);
            $teams[] = $entryModel;
        }

        return $teams;
    }

    /**
     * Get Team by given Id.
     *
     * @param int $id
     * @return TeamModel|null
     */
    public function getTeamById(int $id): ?TeamModel
    {
        $team = $this->getTeams(['id' => $id]);

        return reset($team) ?: null;
    }

    /**
     * Updates the position of the team.
     *
     * @param int $id
     * @param int $position
     */
    public function updatePositionById(int $id, int $position)
    {
        $this->db()->update('kvteam')
            ->values(['position' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Sort teams.
     *
     * @param int $teamId
     * @param int $i
     */
    public function sort(int $teamId, int $i)
    {
        $this->db()->update('kvteam')
            ->values(['position' => $i])
            ->where(['id' => $teamId])
            ->execute();
    }

    /**
     * Inserts or updates Team Model.
     *
     * @param TeamModel $team
     */
    public function save(TeamModel $team)
    {
        $fields = [
            'title' => $team->getTitle(),
            'userIds' => $team->getUserIds(),
            'position' => $team->getPosition()
        ];

        if ($team->getId()) {
            $this->db()->update('kvteam')
                ->values($fields)
                ->where(['id' => $team->getId()])
                ->execute();
        } else {
            $this->db()->insert('kvteam')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Delete Team with given Id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('kvteam')
            ->where(['id' => $id])
            ->execute();
    }
}
