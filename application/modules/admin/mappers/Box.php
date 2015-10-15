<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Box as BoxModel;

defined('ACCESS') or die('no direct access');

/**
 * The box mapper class.
 */
class Box extends \Ilch\Mapper
{
    /**
     * Get box lists for overview.
     *
     * @param string $locale
     * @return BoxModel[]|array
     */
    public function getBoxList($locale)
    {
        $sql = 'SELECT bc.title, b.id FROM [prefix]_boxes as b
                LEFT JOIN [prefix]_boxes_content as bc ON b.id = bc.box_id
                AND bc.locale = "'.$this->db()->escape($locale).'"
                GROUP BY b.id';
        $boxArray = $this->db()->queryArray($sql);

        if (empty($boxArray)) {
            return array();
        }

        $boxes = array();

        foreach ($boxArray as $boxRow) {
            $boxModel = new BoxModel();
            $boxModel->setId($boxRow['id']);
            $boxModel->setTitle($boxRow['title']);

            $boxes[] = $boxModel;
        }

        return $boxes;
    }

    /**
     * Returns box model found by the key.
     *
     * @param string $id
     * @param string $locale
     * @return BoxModel|null
     */
    public function getBoxByIdLocale($id, $locale = '')
    {
        $sql = 'SELECT * FROM [prefix]_boxes as b
                INNER JOIN [prefix]_boxes_content as bc ON b.id = bc.box_id
                WHERE b.`id` = "'.(int) $id.'" AND bc.locale = "'.$this->db()->escape($locale).'"';
        $boxRow = $this->db()->queryRow($sql);

        if (empty($boxRow)) {
            return null;
        }

        $boxModel = new BoxModel();
        $boxModel->setId($boxRow['id']);
        $boxModel->setTitle($boxRow['title']);
        $boxModel->setContent($boxRow['content']);
        $boxModel->setLocale($boxRow['locale']);

        return $boxModel;
    }

    /**
     * Inserts or updates a box model in the database.
     *
     * @param BoxModel $box
     */
    public function save(BoxModel $box)
    {
        if ($box->getId()) {
            if ($this->getBoxByIdLocale($box->getId(), $box->getLocale())) {
                $this->db()->update('boxes_content')
                    ->values(array('title' => $box->getTitle(), 'content' => $box->getContent()))
                    ->where(array('box_id' => $box->getId(), 'locale' => $box->getLocale()))
                    ->execute();
            } else {
                $this->db()->insert('boxes_content')
                    ->values(array('box_id' => $box->getId(), 'title' => $box->getTitle(), 'content' => $box->getContent(), 'locale' => $box->getLocale()))
                    ->execute();
            }
        } else {
            $date = new \Ilch\Date();
            $boxId = $this->db()->insert('boxes')
                ->values(array('date_created' => $date->toDb()))
                ->execute();

            $this->db()->insert('boxes_content')
                ->values(array('box_id' => $boxId, 'title' => $box->getTitle(), 'content' => $box->getContent(), 'locale' => $box->getLocale()))
                ->execute();
        }
    }

    public function delete($id)
    {
        $this->db()->delete('boxes')
            ->where(array('id' => $id))
            ->execute();

        $this->db()->delete('boxes_content')
            ->where(array('box_id' => $id))
            ->execute();
    }
}
