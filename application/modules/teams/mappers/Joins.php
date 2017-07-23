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
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|array
     */
    public function getApplications($where = [], $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('teams_joins')
            ->where($where)
            ->order(['id' => 'ASC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
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
            $entryModel->setGender($entries['gender']);
            $entryModel->setBirthday($entries['birthday']);
            $entryModel->setPlace($entries['place']);
            $entryModel->setSkill($entries['skill']);
            $entryModel->setTeamId($entries['teamId']);
            $entryModel->setLocale($entries['locale']);
            $entryModel->setDateCreated($entries['dateCreated']);
            $entryModel->setText($entries['text']);
            $entryModel->setDecision($entries['decision']);
            $entryModel->setUndecided($entries['undecided']);
            $teams[] = $entryModel;
        }

        return $teams;
    }

    /**
     * Gets the Joins.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|array
     */
    public function getJoins($where = [], $pagination = null)
    {
        $whereMerged = array_merge($where, ['undecided' => 1]);
        return $this->getApplications($whereMerged, $pagination);
    }

    /**
     * Gets the history of applications.
     *
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|array
     */
    public function getApplicationHistory($pagination = null)
    {
        return $this->getApplications(['undecided' => 0], $pagination);
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
     * Get Join in history by given Id.
     *
     * @param integer $id
     * @return JoinsModel|null
     */
    public function getJoinInHistoryById($id)
    {
        $application = $this->getApplications(['id' => $id, 'undecided' => 0]);

        return reset($application);
    }

    /**
     * Gets the history of applications of a specific user by userId.
     *
     * @param int $userId
     * @param \Ilch\Pagination|null $pagination
     * @return JoinsModel[]|array
     */
    public function getApplicationHistoryByUserId($userId, $pagination = null)
    {
        return $this->getApplications(['userId' => $userId, 'undecided' => 0], $pagination);
    }

    /**
     * Get Age from date.
     *
     * @param string $date
     * @return JoinsModel|null
     */
    public function getAge($date) {
        return intval(date('Y', time() - strtotime($date))) - 1970;
    }

    /**
     * Update status of join/application
     * 1 = accepted, 2 = declined
     *
     * @param int $id
     * @param int $decision
     */
    public function updateDecision($id, $decision) {
        $this->db()->update('teams_joins')
            ->values(['decision' => $decision, 'undecided' => 0])
            ->where(['id' => $id])
            ->execute();
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
            'gender' => $join->getGender(),
            'birthday' => $join->getBirthday(),
            'place' => $join->getPlace(),
            'skill' => $join->getSkill(),
            'teamId' => $join->getTeamId(),
            'locale' => $join->getLocale(),
            'dateCreated' => $join->getDateCreated(),
            'text' => $join->getText(),
            'decision' => $join->getDecision(),
            'undecided' => $join->getUndecided()
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
    
    /**
     * Delete all applications/joins from the history.
     * In other words these are the ones with undecided = 0 (accept, reject).
     *
     */
    public function clearHistory()
    {
        $this->db()->delete('teams_joins')
            ->where(['undecided' => 0])
            ->execute();
    }
}
