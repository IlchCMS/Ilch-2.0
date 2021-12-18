<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Guestbook\Mappers;

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
    public function getEntries($where = [], $pagination = null)
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
     * @param integer $id
     */
    public function delete($id)
    {
        return $this->db()->delete('gbook')
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Reset the Vote counts.
     *
     * @param null|integer $setfree
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.11.0
     */
    public function reset($setfree = null)
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
