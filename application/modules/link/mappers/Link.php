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
    public function getLinks($where = array())
    {
        $linkArray = $this->db()->select('*')->from('links')->where($where)->execute()->fetchRows();

        if (empty($linkArray)) {
            return null;
        }

        $links = array();

        foreach ($linkArray as $linkRow) {
            $linkModel = new LinkModel();
            $linkModel->setId($linkRow['id']);
            $linkModel->setCatId($linkRow['cat_id']);
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
        $links = $this->getLinks(array('id' => $id));
        return reset($links);
    }

    /**
     * Inserts or updates link model.
     *
     * @param LinkModel $link
     */
    public function save(LinkModel $link)
    {
        $fields = array
        (
            'name' => $link->getName(),
            'link' => $link->getLink(),
            'banner' => $link->getBanner(),
            'desc' => $link->getDesc(),
            'cat_id' => $link->getCatId(),
            'hits' => $link->getHits()
        );

        if ($link->getId()) {
            $this->db()->update('links')
                ->values($fields)
                ->where(array('id' => $link->getId()))
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
            ->where(array('id' => $id))
            ->execute();
    }
}
