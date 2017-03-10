<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Mappers;

use Modules\Teams\Models\Joins as JoinsModel;

class Joins extends \Ilch\Mapper
{
    /**
     * Gets the Joins.
     *
     * @param array $where
     * @return JoinsModel[]|array
     */
    public function getJoins($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('teams_joins')
            ->where($where)
            ->order(['id' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $teams = [];
        foreach ($entryArray as $entries) {
            $entryModel = new JoinsModel();
            $entryModel->setId($entries['id']);
            $entryModel->setUserId($entries['userId']);
            $entryModel->setName($entries['name']);
            $entryModel->setEmail($entries['email']);
            $entryModel->setAge($entries['age']);
            $entryModel->setPlace($entries['place']);
            $entryModel->setSkill($entries['skill']);
            $entryModel->setTeamId($entries['teamId']);
            $entryModel->setText($entries['text']);
            $teams[] = $entryModel;
        }

        return $teams;
    }

    /**
     * Get Join by given Id.
     *
     * @param integer $id
     * @return JoinsModel|null
     */
    public function getJoinById($id)
    {
        $join = $this->getJoins(['id' => $id]);

        return reset($join);
    }

    /**
     * Inserts Join Model.
     *
     * @param JoinsModel $join
     */
    public function save(JoinsModel $join)
    {
        $fields = [
            'userId' => $join->getUserId(),
            'name' => $join->getName(),
            'email' => $join->getEmail(),
            'age' => $join->getAge(),
            'place' => $join->getPlace(),
            'skill' => $join->getSkill(),
            'teamId' => $join->getTeamId(),
            'text' => $join->getText()
        ];

        $this->db()->insert('teams_joins')
            ->values($fields)
            ->execute();
    }

    /**
     * Delete Join with given Id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('teams_joins')
            ->where(['id' => $id])
            ->execute();

    }
}
