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
    public function getTeams($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('teams')
            ->where($where)
            ->order(['id' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $teams = [];
        foreach ($entryArray as $entries) {
            $entryModel = new TeamsModel();
            $entryModel->setId($entries['id']);
            $entryModel->setName($entries['name']);
            $entryModel->setImg($entries['img']);
            $entryModel->setLeader($entries['leader']);
            $entryModel->setCoLeader($entries['coLeader']);
            $entryModel->setGroupId($entries['groupId']);
            $entryModel->setOptIn($entries['optIn']);
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
     * Inserts or updates Team Model.
     *
     * @param TeamsModel $team
     */
    public function save(TeamsModel $team)
    {
        $fields = [
            'name' => $team->getName(),
            'img' => $team->getImg(),
            'leader' => $team->getLeader(),
            'coLeader' => $team->getCoLeader(),
            'groupId' => $team->getGroupId(),
            'optIn' => $team->getOptIn()
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
