<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Link\Mappers;

use Link\Models\Link as LinkModel;

defined('ACCESS') or die('no direct access');

/**
 * The link mapper class.
 *
 * @package ilch
 */
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
        $linkArray = $this->db()->selectArray('*', 'links', $where);

        if (empty($linkArray)) {
            return array();
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
        if ($link->getId()) {
            $this->db()->update
            (
                array
                (
                    'name' => $link->getName(),
                    'link' => $link->getLink(),
                    'banner' => $link->getBanner(),
                    'desc' => $link->getDesc(),
                    'cat_id' => $link->getCatId(),
                    'hits' => $link->getHits()
                ),
                'links',
                array
                (
                    'id' => $link->getId(),
                )
            );
        } else {
            $this->db()->insert
            (
                array
                (
                    'name' => $link->getName(),
                    'link' => $link->getLink(),
                    'banner' => $link->getBanner(),
                    'desc' => $link->getDesc(),
                    'cat_id' => $link->getCatId(),
                    'hits' => $link->getHits()
                ),
                'links'
            );
        }
    }

    /**
     * Deletes link with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete
        (
            'links',
            array('id' => $id)
        );
    }
}
