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
    public function getCategories(array $where = []): array
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
     * Get Ticket by given id.
     *
     * @param int $id
     * @return CatModel|null
     */
    public function getCategoryById(int $id): ?CatModel
    {
        $categories = $this->getCategories(['id' => $id]);

        if (empty($categories)) {
            return null;
        }

        return $categories[0];
    }

    /**
     * Inserts or updates Ticket Model.
     *
     * @param CatModel $cat
     */
    public function save(CatModel $cat): void
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
     * Delete Ticket with given id.
     *
     * @param int $id
     */
    public function delete(int $id): void
    {
        $this->db()->delete('kvticket_cat')
            ->where(['id' => $id])
            ->execute();
    }
}
