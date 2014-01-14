<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Shoutbox\Mappers;

use Shoutbox\Models\Shoutbox as ShoutboxModel;

defined('ACCESS') or die('no direct access');

class Shoutbox extends \Ilch\Mapper
{
    /**
     * Gets the Shoutbox.
     *
     * @param array $where
     * @return ShoutboxModel[]|array
     */
    public function getShoutbox($where = array(), $limit = null)
    {
        $entryArray = $this->db()->selectArray
        (
            '*',
            'shoutbox',
            $where,
            array('id' => 'DESC'),
            $limit
        );

        if (empty($entryArray)) {
            return array();
        }

        $shoutbox = array();

        foreach ($entryArray as $entries) {
            $entryModel = new ShoutboxModel();
            $entryModel->setId($entries['id']);
            $entryModel->setUid($entries['user_id']);
            $entryModel->setName($entries['name']);
            $entryModel->setTextarea($entries['textarea']);
            $entryModel->setTime($entries['time']);
            $shoutbox[] = $entryModel;
        }

        return $shoutbox;
    }

    /**
     * Gets shoutbox.
     *
     * @param integer $id
     * @return ShoutboxModel|null
     */
    public function getShoutboxById($id)
    {
        $shoutbox = $this->getShoutbox(array('id' => $id));
        return reset($shoutbox);
    }

    /**
     * Insert shoutbox model.
     *
     * @param ShoutboxModel $shoutbox
     */
    public function save(ShoutboxModel $shoutbox)
    {
        $date = new \Ilch\Date();

        $this->db()->insert
        (
            array
            (
                'user_id' => $shoutbox->getUid(),
                'name' => $shoutbox->getName(),
                'textarea' => $shoutbox->getTextarea(),
                'time' => $date->toDb(),
            ),
            'shoutbox'
        );
    }

    /**
     * Deletes shoutbox with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete
        (
            'shoutbox',
            array('id' => $id)
        );
    }
}
