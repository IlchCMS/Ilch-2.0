<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Guestbook\Mappers;

use Ilch\Database\Mysql\Result;
use Modules\Guestbook\Models\Entry as GuestbookModel;

class Guestbook extends \Ilch\Mapper
{
    /**
     * Gets the guestbook entries.
     *
     * @param array $where
     * @param \Ilch\Pagination|null $pagination
     * @return GuestbookModel[]|array
     */
    public function getEntries(array $where = [], ?\Ilch\Pagination $pagination = null): array
    {
        $select = $this->db()->select('*')
            ->from('gbook')
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
        $entry = [];

        foreach ($entryArray as $entries) {
            $entryModel = new GuestbookModel();
            $entryModel->setId($entries['id']);
            $entryModel->setEmail($entries['email']);
            $entryModel->setText($entries['text']);
            $entryModel->setDatetime($entries['datetime']);
            $entryModel->setHomepage($entries['homepage']);
            $entryModel->setName($entries['name']);
            $entryModel->setFree($entries['setfree']);
            $entry[] = $entryModel;
        }

        return $entry;
    }


    /**
     * @param int|GuestbookModel $id
     * @param int $setfree
     * @return boolean
     */
    public function updateSetfree($id, int $setfree = -1): bool
    {
        if ($setfree !== -1) {
            $setfreeNow = $setfree;
        } else {
            if (is_a($id, GuestbookModel::class)) {
                $setfree = $id->getFree();
            } else {
                $setfree = (int) $this->db()->select('setfree')
                    ->from('gbook')
                    ->where(['id' => (int)$id])
                    ->execute()
                    ->fetchCell();
            }

            if ($setfree === 1) {
                $setfreeNow = 0;
            } else {
                $setfreeNow = 1;
            }
        }
        if (is_a($id, GuestbookModel::class)) {
            $id = $id->getId();
        }

        return $this->db()->update('gbook')
            ->values(['setfree' => $setfreeNow])
            ->where(['id' => (int)$id])
            ->execute();
    }

    /**
     * Inserts or updates gustebook entry.
     *
     * @param GuestbookModel $model
     */
    public function save(GuestbookModel $model)
    {
        $fields = [
            'email' => $model->getEmail(),
            'text' => $model->getText(),
            'datetime' => $model->getDatetime(),
            'homepage' => $model->getHomepage(),
            'name' => $model->getName(),
            'setfree' => $model->getFree()
        ];

        if ($model->getId()) {
            $this->db()->update('gbook')
                ->values($fields)
                ->where(['id' => $model->getId()])
                ->execute();
        } else {
            $this->db()->insert('gbook')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes the guestbook entry.
     *
     * @param int $id
     * @return Result|int
     */
    public function delete(int $id)
    {
        return $this->db()->delete('gbook')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Reset the Vote counts.
     *
     * @param int|null $setfree
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 1.11.0
     */
    public function reset(?int $setfree = null): bool
    {
        if ($setfree == null) {
            $this->db()->truncate('[prefix]_gbook');
            return $this->db()->queryMulti('ALTER TABLE `[prefix]_gbook` auto_increment = 1;');
        } else {
            return $this->db()->delete('gbook')
                ->where(['setfree' => $setfree])
                ->execute();
        }
    }
}
