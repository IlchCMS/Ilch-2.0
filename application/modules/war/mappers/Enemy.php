<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Mappers;

use Modules\War\Models\Enemy as EnemyModel;

class Enemy extends \Ilch\Mapper
{
    /**
     * Gets the Enemy
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return EnemyModel[]|array
     */
    public function getEnemy($where = array(), $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('war_enemy')
            ->where($where)
            ->order(array('id' => 'DESC'));

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

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new EnemyModel();
            $entryModel->setId($entries['id']);
            $entryModel->setEnemyName($entries['name']);
            $entryModel->setEnemyTag($entries['tag']);
            $entryModel->setEnemyImage($entries['image']);
            $entryModel->setEnemyHomepage($entries['homepage']);
            $entryModel->setEnemyContactName($entries['contact_name']);
            $entryModel->setEnemyContactEmail($entries['contact_email']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets the Enemy List
     *
     * @param \Ilch\Pagination|null $pagination
     * @return EnemyModel[]|array
     */
    public function getEnemyList($pagination = null)
    {
        $sql = 'SELECT SQL_CALC_FOUND_ROWS e.id, e.name, e.tag, e.image, e.homepage, e.contact_name, e.contact_email, m.url, m.url_thumb
                FROM `[prefix]_war_enemy` as e
                LEFT JOIN [prefix]_media m ON e.image = m.url
                ORDER by e.id DESC
                LIMIT '.implode(',',$pagination->getLimit());

        $enemyArray = $this->db()->queryArray($sql);
        $pagination->setRows($this->db()->querycell('SELECT FOUND_ROWS()'));

        if (empty($enemyArray)) {
            return null;
        }

        $entry = array();

        foreach ($enemyArray as $entries) {
            $entryModel = new EnemyModel();
            $entryModel->setId($entries['id']);
            $entryModel->setEnemyName($entries['name']);
            $entryModel->setEnemyTag($entries['tag']);
            $entryModel->setEnemyImage($entries['url_thumb']);
            $entryModel->setEnemyHomepage($entries['homepage']);
            $entryModel->setEnemyContactName($entries['contact_name']);
            $entryModel->setEnemyContactEmail($entries['contact_email']);
            $entry[] = $entryModel;
        }

        return $entry;
    }

    /**
     * Gets Enemy by id.
     *
     * @param integer $id
     * @return EnemyModel|null
     */
    public function getEnemyById($id)
    {
        $enemyRow = $this->db()->select('*')
            ->from('war_enemy')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($enemyRow)) {
            return null;
        }

        $enemyModel = new EnemyModel();
        $enemyModel->setId($enemyRow['id']);
        $enemyModel->setEnemyName($enemyRow['name']);
        $enemyModel->setEnemyTag($enemyRow['tag']);
        $enemyModel->setEnemyImage($enemyRow['image']);
        $enemyModel->setEnemyHomepage($enemyRow['homepage']);
        $enemyModel->setEnemyContactName($enemyRow['contact_name']);
        $enemyModel->setEnemyContactEmail($enemyRow['contact_email']);

        return $enemyModel;
    }

    /**
     * Inserts or updates enemy entry.
     *
     * @param EnemyModel $model
     */
    public function save(EnemyModel $model)
    {
        $fields = array
        (
            'name' => $model->getEnemyName(),
            'tag' => $model->getEnemyTag(),
            'image' => $model->getEnemyImage(),
            'homepage' => $model->getEnemyHomepage(),
            'contact_name' => $model->getEnemyContactName(),
            'contact_email' => $model->getEnemyContactEmail(),
        );

        if ($model->getId()) {
            $this->db()->update('war_enemy')
                ->values($fields)
                ->where(array('id' => $model->getId()))
                ->execute();
        } else {
            $this->db()->insert('war_enemy')
                ->values($fields)
                ->execute();
        }
    }

    public function delete($id)
    {
        $this->db()->delete('war_enemy')
            ->where(array('id' => $id))
            ->execute();
    }
}
