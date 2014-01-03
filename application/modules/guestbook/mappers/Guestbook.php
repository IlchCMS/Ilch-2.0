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
     * @return Guestbook\Models\Entry[]|null
     */
    public function getEntries()
    {
        $sql = 'SELECT *
                FROM [prefix]_gbook
                WHERE setfree = 0
                ORDER by id DESC';
        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
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
            $entry[] = $entryModel;
        }

        return $entry;
    }
    
    /**
     * Gets all new entries.
     *
     * @return Guestbook\Models\Settings[]|null
     */
    public function getNewEntries()
    {
        $sql = 'SELECT *
                FROM [prefix]_gbook
                WHERE setfree = 1
                ORDER by id DESC';
        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
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
     * Saves the guestbook entry.
     *
     * @param array $datas
     * @return integer
     */
    public function saveEntry(array $datas)
    {
        return $this->db()->insert($datas, 'gbook');
    }

    /**
     * Deletes the guestbook entry.
     *
     * @param integer $id
     */
    public function deleteEntry($id)
    {
        return $this->db()->delete('gbook', array('id' => $id));
    }
    
    public function saveSetfree(array $datas, array $id)
    {
       return $this->db()->update($datas, 'gbook', $id);
    }
}
