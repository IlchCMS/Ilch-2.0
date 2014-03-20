<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

namespace Eventplaner\Mappers;

use Eventplaner\Models\Charakter as CharakterModel;

defined('ACCESS') or die('no direct access');

class Eventplaner extends \Ilch\Mapper
{
    public function getList($where = array())
    {
        $entryArray = $this->db()->selectArray('*')
            ->from('ep_charakter')
            ->where($where)
            ->order(array('id' => 'DESC'))
            ->execute();

        if (empty($entryArray)) {
            return array();
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new CharakterModel();
            $entryModel->setId($entries['id']);
            $entryModel->setUserID($entries['user_id']);
            $entryModel->setName($entries['name']);
            $entryModel->setCreate($entries['create']);
            $entryModel->setEdit($entries['edit']);
            $entry[] = $entryModel;

        }

        return $entry;
    }

    public function delete($id)
    {
        return $this->db()->delete('gbook')
            ->where(array('id' => $id))
            ->execute();
    }
}
