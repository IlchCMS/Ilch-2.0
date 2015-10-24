<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Mappers;

use Modules\Shoutbox\Models\Shoutbox as ShoutboxModel;

class Shoutbox extends \Ilch\Mapper
{
    /**
     * Gets the Shoutbox.
     *
     * @return ShoutboxModel[]|array
     */
    public function getShoutbox()
    {
        $entryArray = $this->db()->select('*')
                ->from('shoutbox')
                ->order(array('id' => 'DESC'))
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
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
     * Gets the Shoutbox.
     *
     * @return ShoutboxModel[]|array
     */
    public function getShoutboxLimit($limit = null)
    {
        $entryArray = $this->db()->select('*')
                ->from('shoutbox')
                ->order(array('id' => 'DESC'))
                ->limit($limit)
                ->execute()
                ->fetchRows();

        if (empty($entryArray)) {
            return null;
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

        $this->db()->insert('shoutbox')
            ->values(array(
                'user_id' => $shoutbox->getUid(),
                'name' => $shoutbox->getName(),
                'textarea' => $shoutbox->getTextarea(),
                'time' => $date->toDb(),
            ))
            ->execute();
    }

    /**
     * Deletes shoutbox with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('shoutbox')
            ->where(array('id' => $id))
            ->execute();
    }
}
