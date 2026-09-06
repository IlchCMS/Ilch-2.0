<?php
/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvticket\Mappers;

use Modules\Kvticket\Models\Ticket as TicketModel;

class Ticket extends \Ilch\Mapper
{
    /**
     * Gets the Tickets.
     *
     * @param array $where
     * @param array $order
     * @return TicketModel[]|array
     */
    public function getTickets($where = [], $order = ['created_at' => 'DESC'])
    {
        $entryArray = $this->db()->select('*')
            ->from('kvticket')
            ->where($where)
            ->order($order)
            ->execute()
            ->fetchRows();

        $tickets = [];
        if (empty($entryArray)) {
            return $tickets;
        }

        foreach ($entryArray as $entries) {
            $entryModel = new TicketModel();
            $entryModel->setId($entries['id'])
                ->setTitle($entries['title'])
                ->setText($entries['text'])
                ->setStatus($entries['status'])
                ->setEditor($entries['editor'])
                ->setCreator($entries['creator'])
                ->setCat($entries['cat'])
                ->setCreatedAt($entries['created_at'])
                ->setUpdatedAt($entries['updated_at']);
            $tickets[] = $entryModel;
        }

        return $tickets;
    }

    /**
     * Get Ticket by given Id.
     *
     * @param integer $id
     * @return TicketModel|null
     */
    public function getTicketById($id)
    {
        $team = $this->getTickets(['id' => $id]);

        return reset($team);
    }

    /**
     * Inserts or updates Ticket Model.
     *
     * @param TicketModel $ticket
     */
    public function save(TicketModel $ticket)
    {
        $fields = [
            'title' => $ticket->getTitle(),
            'text' => $ticket->getText(),
            'status' => $ticket->getStatus(),
            'editor' => $ticket->getEditor(),
            'creator' => $ticket->getCreator(),
            'cat' => $ticket->getCat()
        ];

        if ($ticket->getId()) {
            $this->db()->update('kvticket')
                ->values($fields)
                ->where(['id' => $ticket->getId()])
                ->execute();
        } else {
            $this->db()->insert('kvticket')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Delete Ticket with given Id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('kvticket')
            ->where(['id' => $id])
            ->execute();

    }
}
