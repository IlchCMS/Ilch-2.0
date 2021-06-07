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
    public function getEnemy($where = [], $pagination = null)
    {
        $select = $this->db()->select('*')
            ->from('war_enemy')
            ->where($where)
            ->order(['id' => 'DESC']);

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

        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EnemyModel();
            $entryModel->setId($entries['id'])
                ->setEnemyName($entries['name'])
                ->setEnemyTag($entries['tag'])
                ->setEnemyImage($entries['image'])
                ->setEnemyHomepage($entries['homepage'])
                ->setEnemyContactName($entries['contact_name'])
                ->setEnemyContactEmail($entries['contact_email']);
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
        $sql = $this->db()->select(['e.id', 'e.name', 'e.tag', 'e.image', 'e.homepage', 'e.contact_name', 'e.contact_email', 'm.url', 'm.url_thumb'])
            ->from(['e' => 'war_enemy'])
            ->join(['m' => 'media'], 'e.image = m.url', 'LEFT')
            ->order(['e.id' => 'DESC']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $enemyArray = $result->fetchRows();

        if (empty($enemyArray)) {
            return null;
        }

        $entries = [];

        foreach ($enemyArray as $entry) {
            $enemyModel = new EnemyModel();
            $enemyModel->setId($entry['id'])
                ->setEnemyName($entry['name'])
                ->setEnemyTag($entry['tag'])
                ->setEnemyImage($entry['image'])
                ->setEnemyHomepage($entry['homepage'])
                ->setEnemyContactName($entry['contact_name'])
                ->setEnemyContactEmail($entry['contact_email']);
            $entries[] = $enemyModel;
        }

        return $entries;
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
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($enemyRow)) {
            return null;
        }

        $enemyModel = new EnemyModel();
        $enemyModel->setId($enemyRow['id'])
            ->setEnemyName($enemyRow['name'])
            ->setEnemyTag($enemyRow['tag'])
            ->setEnemyImage($enemyRow['image'])
            ->setEnemyHomepage($enemyRow['homepage'])
            ->setEnemyContactName($enemyRow['contact_name'])
            ->setEnemyContactEmail($enemyRow['contact_email']);

        return $enemyModel;
    }

    /**
     * Inserts or updates enemy entry.
     *
     * @param EnemyModel $model
     */
    public function save(EnemyModel $model)
    {
        $fields = [
            'name' => $model->getEnemyName(),
            'tag' => $model->getEnemyTag(),
            'image' => $model->getEnemyImage(),
            'homepage' => $model->getEnemyHomepage(),
            'contact_name' => $model->getEnemyContactName(),
            'contact_email' => $model->getEnemyContactEmail()
        ];

        if ($model->getId()) {
            $this->db()->update('war_enemy')
                ->values($fields)
                ->where(['id' => $model->getId()])
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
            ->where(['id' => $id])
            ->execute();
    }
}
