<?php
/**
 * Default opponent mapper
 *
 * @copyright Ilch 2.0
 * @package ilch
 */
namespace Modules\Matches\Mappers;

use \Modules\Matches\Models\Opponent as Model;
use \Exception;

class Opponent extends \Ilch\Mapper
{
    /**
     * @var string The MySQL Table this mapper mainly uses
     */
    protected $db_table = "matches_opponents";

    /**
     * @var array Default criteria for opponents (default scope)
     */
    protected $default_criteria = array(
        'where' => '',
        'order' => array('name' => 'ASC'),
        'limit' => '',
    );

    /**
     * Finds the desired opponent
     *
     * @param integer $id The id of the opponent you wish to find
     *
     * @return object|null The opponent object or null if no opponent with $id was found
     */
    public function find($id)
    {
        $select = $this->db()->select();
        $result = $select->fields(['id', 'name', 'short_name', 'logo', 'url'])
            ->from($this->db_table)
            ->where(['id' => $id])
            ->limit(1)
            ->execute();
        if ($result->getNumRows() === 1) {
            $data = $result->fetchAssoc();
            $opponent = new Model;
            $opponent
                ->setId($data['id'])
                ->setName($data['name'])
                ->setShortName($data['short_name'])
                ->setLogo($data['logo'])
                ->setUrl($data['url']);
            return $opponent;
        } else {
            throw new Exception("Couldn't find opponent");
        }
        return null;
    }

    /**
     * Finds all opponents
     *
     * @param array|null $criteria Find teams matching this criteria
     *                             NOTE: Limit gets overwritten if you pass in a Pagination instance
     * @param \Ilch\Pagination|null $pagination Pagination instance
     *
     * @return array Empty array or array with \Matches\Models\Opponent instances
     */
    public function findAll($criteria = null, $pagination = null)
    {
        $select = $this->db()->select();
        $result = $select->fields(['id', 'name', 'short_name', 'logo', 'url'])
            ->from($this->db_table);
        $dbCriteria = $this->default_criteria;
        if ($criteria !== null && is_array($criteria)) {
            $dbCriteria = array_merge($dbCriteria, $criteria);
        }
        if ($dbCriteria['where']) {
            $result->where($dbCriteria['where']);
        }
        if ($dbCriteria['order']) {
            $result->order($dbCriteria['order']);
        }
        if ($dbCriteria['limit']) {
            $result->limit($dbCriteria['limit']);
        }
        if ($pagination !== null) {
            $result->limit($pagination->getLimit());
            $pagination->setRows($result->getNumRows());
        }
        $result = $result->execute();
        $opponents = array();
        while ($row = $result->fetchAssoc()) {
            $opponent = new Model;
            $opponent
                ->setId($row['id'])
                ->setName($row['name'])
                ->setShortName($row['short_name'])
                ->setLogo($row['logo'])
                ->setUrl($row['url']);
            $opponents[] = $opponent;
        }
        return $opponents;
    }

    /**
     * Saves or updates an opponent to the database
     *
     * @param \Matches\Models\Opponent $opponent
     *
     * @return \Matches\Models\Opponent
     */
    public function save($opponent)
    {
        if ($opponent->getId() === 0) {
            return $this->insert($opponent);
        } else {
            return $this->update($opponent);
        }
    }

    /**
     * Inserts a new opponent to the database
     *
     * @param \Matches\Models\Opponent $opponent
     *
     * @throws \Exception
     *
     * @return \Matches\Models\Opponent
     */
    protected function insert($opponent)
    {
        $insert = $this->db()->insert();
        $opponentId = $insert->into($this->db_table)
            ->values([
                'name'      => $opponent->getName(),
                'short_name'=> $opponent->getShortName(),
                'logo'      => $opponent->getLogo(),
                'website'   => $opponent->getWebsite(),
            ])->execute();
        if (!$opponentId) {
            throw new Exception("Couldn't create opponent");
        }
        $opponent->setId($opponentId);
        return $opponent;
    }

    /**
     * Updates opponentData to the database
     *
     * @param \Matches\Models\Opponent $opponent
     *
     * @throws \Exception
     *
     * @return \Matches\Models\Opponent
     */
    protected function update($opponent)
    {
        throw new Exception("Couldn't update opponent.");
    }
}
