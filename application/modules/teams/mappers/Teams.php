<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Mappers;

use Modules\Teams\Models\Teams as TeamsModel;

class Teams extends \Ilch\Mapper
{
    /**
     * Gets the Teams.
     *
     * @param array $where
     * @return TeamsModel[]|array
     */
    public function getTeams($where = []): array
    {
        $entryArray = $this->db()->select('*')
            ->from('teams')
            ->where($where)
            ->order(['position' => 'ASC'])
            ->execute()
            ->fetchRows();

        $teams = [];

        if (empty($entryArray)) {
            return $teams;
        }

        foreach ($entryArray as $entries) {
            $entryModel = new TeamsModel();
            $entryModel->setId($entries['id'])
                ->setPosition($entries['position'])
                ->setName($entries['name'])
                ->setImg($entries['img'])
                ->setLeader($entries['leader'])
                ->setCoLeader($entries['coLeader'])
                ->setGroupId($entries['groupId'])
                ->setOptShow($entries['optShow'])
                ->setOptIn($entries['optIn'])
                ->setNotifyLeader($entries['notifyLeader']);
            $teams[] = $entryModel;
        }

        return $teams;
    }

    /**
     * Get Team by given Id.
     *
     * @param integer $id
     * @return TeamsModel|null
     */
    public function getTeamById($id)
    {
        $team = $this->getTeams(['id' => $id]);

        return reset($team);
    }

    /**
     * Get Team by given group id.
     *
     * @param integer $id
     * @return TeamsModel|null
     */
    public function getTeamByGroupId($id)
    {
        $team = $this->getTeams(['groupId' => $id]);

        return reset($team);
    }

    /**
     * Updates the position of the team.
     *
     * @param int $id , int $position
     * @param int $position
     */
    public function updatePositionById($id, $position) {
        $this->db()->update('teams')
            ->values(['position' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Delete/Unlink Image by Id.
     *
     * @param int $id
     */
    public function delImageById($id)
    {
        $row = $this->db()->select('*')
            ->from('teams')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (file_exists($row['img'])) {
            unlink($row['img']);
        }

        $this->db()->update('teams')
            ->values(['img' => ''])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Sort teams.
     *
     * @param int $teamId
     * @param int $key
     */
    public function sort($teamId, $key)
    {
        $this->db()->update('teams')
            ->values(['position' => $key])
            ->where(['id' => $teamId])
            ->execute();
    }

    /**
     * Inserts or updates Team Model.
     *
     * @param TeamsModel $team
     */
    public function save(TeamsModel $team)
    {
        $fields = [
            'name' => $team->getName(),
            'position' => $team->getPosition(),
            'img' => $team->getImg(),
            'leader' => $team->getLeader(),
            'coLeader' => $team->getCoLeader(),
            'groupId' => $team->getGroupId(),
            'optShow' => $team->getOptShow(),
            'optIn' => $team->getOptIn(),
            'notifyLeader' => $team->getNotifyLeader()
        ];

        if ($team->getId()) {
            $this->db()->update('teams')
                ->values($fields)
                ->where(['id' => $team->getId()])
                ->execute();
        } else {
            $this->db()->insert('teams')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Delete Team with given Id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $row = $this->db()->select('*')
            ->from('teams')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (file_exists($row['img'])) {
            unlink($row['img']);
        }

        $this->db()->delete('teams')
            ->where(['id' => $id])
            ->execute();

    }
}
