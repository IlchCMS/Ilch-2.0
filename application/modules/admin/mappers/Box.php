<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Box as BoxModel;

class Box extends \Ilch\Mapper
{
    /**
     * Get box lists for overview.
     *
     * @param string $locale
     * @return BoxModel[]|array
     * @throws \Ilch\Database\Exception
     */
    public function getBoxList($locale)
    {
        $boxRows = $this->db()->select('*')
            ->from('modules_boxes_content')
            ->where(['locale' => $locale])
            ->execute()
            ->fetchRows();

        if (empty($boxRows)) {
            return [];
        }

        $boxes = [];

        foreach ($boxRows as $boxRow) {
            $boxModel = new BoxModel();
            $boxModel->setKey($boxRow['key']);
            $boxModel->setModule($boxRow['module']);
            $boxModel->setLocale($boxRow['locale']);
            $boxModel->setName($boxRow['name']);

            $boxes[] = $boxModel;
        }

        return $boxes;
    }

    /**
     * Get box lists for overview.
     *
     * @param string $locale
     * @return BoxModel[]|array
     * @throws \Ilch\Database\Exception
     */
    public function getSelfBoxList($locale)
    {
        $sql = 'SELECT bc.title, b.id FROM [prefix]_boxes as b
                LEFT JOIN [prefix]_boxes_content as bc ON b.id = bc.box_id
                AND bc.locale = "'.$this->db()->escape($locale).'"
                GROUP BY b.id, bc.title
                ORDER by b.id DESC';
        $boxArray = $this->db()->queryArray($sql);

        if (empty($boxArray)) {
            return [];
        }

        $boxes = [];
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
     * @throws \Ilch\Database\Exception
     */
    public function getSelfBoxByIdLocale($id, $locale = '')
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
     * Inserts box model in the database.
     *
     * @param BoxModel $box
     */
    public function install(BoxModel $box)
    {
        foreach ($box->getContent() as $key => $content) {
            foreach ($content as $lang => $value) {
                $this->db()->insert('modules_boxes_content')
                    ->values([
                        'key' => $key,
                        'module' => $box->getModule(),
                        'locale' => $lang,
                        'name' => $value['name']
                    ])
                    ->execute();
            }
        }
    }

    /**
     * Inserts or updates a box model in the database.
     *
     * @param BoxModel $box
     * @throws \Ilch\Database\Exception
     */
    public function save(BoxModel $box)
    {
        if ($box->getId()) {
            if ($this->getSelfBoxByIdLocale($box->getId(), $box->getLocale())) {
                $this->db()->update('boxes_content')
                    ->values(['title' => $box->getTitle(), 'content' => $box->getContent()])
                    ->where(['box_id' => $box->getId(), 'locale' => $box->getLocale()])
                    ->execute();
            } else {
                $this->db()->insert('boxes_content')
                    ->values(['box_id' => $box->getId(), 'title' => $box->getTitle(), 'content' => $box->getContent(), 'locale' => $box->getLocale()])
                    ->execute();
            }
        } else {
            $date = new \Ilch\Date();
            $boxId = $this->db()->insert('boxes')
                ->values(['date_created' => $date->toDb()])
                ->execute();

            $this->db()->insert('boxes_content')
                ->values(['box_id' => $boxId, 'title' => $box->getTitle(), 'content' => $box->getContent(), 'locale' => $box->getLocale()])
                ->execute();
        }
    }

    /**
     * Returns true if there is a module box with a specific value for key and module.
     *
     * @param string $key
     * @param string $module
     * @return bool
     * @since 2.1.19
     */
    public function modulesBoxExists($key, $module)
    {
        return (boolean)$this->db()->select('COUNT(*)')
            ->from('modules_boxes_content')
            ->where(['key' => $key, 'module' => $module])
            ->execute()
            ->fetchCell();
    }

    /**
     * Delete box with specific id.
     *
     * @param $id
     */
    public function delete($id)
    {
        $this->db()->delete('boxes')
            ->where(['id' => $id])
            ->execute();

        $this->db()->delete('boxes_content')
            ->where(['box_id' => $id])
            ->execute();
    }
}
