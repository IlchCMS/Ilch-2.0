<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Guestbook\Mappers;

use Guestbook\Models\Entry as GuestbookModel;

defined('ACCESS') or die('no direct access');

class Guestbook extends \Ilch\Mapper
{
    /**
     * Gets the guestbook entries.
     *
     * @param array $where
     * @return GuestbookModel[]|array
     */
    public function getEntries($where = array())
    {
        $entryArray = $this->db()->selectArray
        (
            '*',
            'gbook',
            $where,
            array('id' => 'DESC')
        );

        if (empty($entryArray)) {
            return array();
        }

        $entry = array();

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
        if ($model->getId()) {
            $this->db()->update
            (
                array
                (
                    'email' => $model->getEmail(),
                    'text' => $model->getText(),
                    'datetime' => $model->getDatetime(),
                    'homepage' => $model->getHomepage(),
                    'name' => $model->getName(),
                    'setfree' => $model->getFree(),
                ),
                'gbook',
                array
                (
                    'id' => $model->getId(),
                )
            );
        } else {
            $this->db()->insert
            (
                array
                (
                    'email' => $model->getEmail(),
                    'text' => $model->getText(),
                    'datetime' => $model->getDatetime(),
                    'homepage' => $model->getHomepage(),
                    'name' => $model->getName(),
                    'setfree' => $model->getFree(),
                ),
                'gbook'
            );
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
            ->where(array('id' => $id))
            ->execute();
    }
}
