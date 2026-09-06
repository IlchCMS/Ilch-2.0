<?php
/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvticket\Mappers;

use Modules\Kvticket\Models\Category as CatModel;

class Category extends \Ilch\Mapper
{
    /**
     * Gets the Tickets.
     *
     * @param array $where
     * @return CatModel[]|array
     */
    public function getCategorys($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('kvticket_cat')
            ->where($where)
            ->execute()
            ->fetchRows();

        $tickets = [];
        if (empty($entryArray)) {
            return $tickets;
        }

        foreach ($entryArray as $entries) {
            $entryModel = new CatModel();
            $entryModel ->setId($entries['id'])
                        ->setTitle($entries['title']);
            $tickets[] = $entryModel;
        }

        return $tickets;
    }

    /**
     * Get Ticket by given Id.
     *
     * @param integer $id
     * @return CatModel|null
     */
    public function getCategoryById($id)
    {
        $team = $this->getCategorys(['id' => $id]);

        return reset($team);
    }

    /**
     * Inserts or updates Ticket Model.
     *
     * @param CatModel $cat
     */
    public function save(CatModel $cat)
    {
        $fields = [
            'title' => $cat->getTitle()
        ];

        if ($cat->getId()) {
            $this->db()->update('kvticket_cat')
                ->values($fields)
                ->where(['id' => $cat->getId()])
                ->execute();
        } else {
            $this->db()->insert('kvticket_cat')
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
        $this->db()->delete('kvticket_cat')
            ->where(['id' => $id])
            ->execute();

    }
}
