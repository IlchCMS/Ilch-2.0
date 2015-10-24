<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Contact\Mappers;

use Modules\Contact\Models\Receiver as ReceiverModel;

/**
 * The receiver mapper class.
 *
 * @package ilch
 */
class Receiver extends \Ilch\Mapper
{
    /**
     * Gets receivers.
     *
     * @return ReceiverModel[]|null
     */
    public function getReceivers()
    {
        $sql = 'SELECT *
                FROM `[prefix]_contact_receivers`';
        $receiverArray = $this->db()->queryArray($sql);

        if (empty($receiverArray)) {
            return null;
        }

        $receivers = array();

        foreach ($receiverArray as $receiverRow) {
            $receiverModel = new ReceiverModel();
            $receiverModel->setId($receiverRow['id']);
            $receiverModel->setName($receiverRow['name']);
            $receiverModel->setEmail($receiverRow['email']);
            $receivers[] = $receiverModel;
        }

        return $receivers;
    }

    /**
     * Gets receiver.
     *
     * @param integer $id
     * @return ReceiverModel|null
     */
    public function getReceiverById($id)
    {
        $sql = 'SELECT *
                FROM [prefix]_contact_receivers
                WHERE id = '.(int)$this->db()->escape($id);
        $receiverRow = $this->db()->queryRow($sql);

        if (empty($receiverRow)) {
            return null;
        }

        $receiverModel = new ReceiverModel();
        $receiverModel->setId($receiverRow['id']);
        $receiverModel->setName($receiverRow['name']);
        $receiverModel->setEmail($receiverRow['email']);

        return $receiverModel;
    }

    /**
     * Inserts or updates receiver model.
     *
     * @param ReceiverModel $receiver
     */
    public function save(ReceiverModel $receiver)
    {
        if ($receiver->getId()) {
            $this->db()->update('contact_receivers')
                ->values(array('name' => $receiver->getName(), 'email' => $receiver->getEmail()))
                ->where(array('id' => $receiver->getId()))
                ->execute();
        } else {
            $this->db()->insert('contact_receivers')
                ->values(array('name' => $receiver->getName(), 'email' => $receiver->getEmail()))
                ->execute();
        }
    }

    /**
     * Deletes receiver with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('contact_receivers')
            ->where(array('id' => $id))
            ->execute();
    }
}
