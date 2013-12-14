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

    public function getEntries() 
    {
        $sql = 'SELECT *
                FROM [prefix]_gbook
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

    public function saveEntry(array $datas) 
    {
        $this->db()->insert($datas, 'gbook');
    }

    public function delEntry($id) 
    {
        $this->db()->delete('gbook', array('id' => $id));
    }

}
