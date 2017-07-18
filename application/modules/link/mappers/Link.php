<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Link\Mappers;

use Modules\Link\Models\Link as LinkModel;

class Link extends \Ilch\Mapper
{
    /**
     * Gets links.
     *
     * @param array $where
     * @return LinkModel[]|null
     */
    public function getLinks($where = [])
    {
        $linkArray = $this->db()->select('*')
            ->from('links')
            ->order(['pos' => 'ASC'])
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($linkArray)) {
            return null;
        }

        $links = [];
        foreach ($linkArray as $linkRow) {
            $linkModel = new LinkModel();
            $linkModel->setId($linkRow['id']);
            $linkModel->setCatId($linkRow['cat_id']);
            $linkModel->setPosition($linkRow['pos']);
            $linkModel->setName($linkRow['name']);
            $linkModel->setDesc($linkRow['desc']);
            $linkModel->setLink($linkRow['link']);
            $linkModel->setBanner($linkRow['banner']);
            $linkModel->setHits($linkRow['hits']);

            $links[] = $linkModel;
        }

        return $links;
    }

    /**
     * Gets link.
     *
     * @param integer $id
     * @return LinkModel|null
     */
    public function getLinkById($id)
    {
        $links = $this->getLinks(['id' => $id]);

        return reset($links);
    }

    /**
     * Updates the position of a link in the database.
     *
     * @param int $id, int $position
     *
     */
    public function updatePositionById($id, $position) {
        $this->db()->update('links')
            ->values(['pos' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates link model.
     *
     * @param LinkModel $link
     */
    public function save(LinkModel $link)
    {
        $fields = [
            'name' => $link->getName(),
            'link' => $link->getLink(),
            'banner' => $link->getBanner(),
            'desc' => $link->getDesc(),
            'cat_id' => $link->getCatId(),
            'pos' => $link->getPosition(),
            'hits' => $link->getHits()
        ];

        if ($link->getId()) {
            $this->db()->update('links')
                ->values($fields)
                ->where(['id' => $link->getId()])
                ->execute();
        } else {
            $this->db()->insert('links')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes link with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('links')
            ->where(['id' => $id])
            ->execute();
    }
}
