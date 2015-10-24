<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Mappers;

use Modules\Downloads\Models\DownloadsItem as DownloadsItem;

class Downloads extends \Ilch\Mapper
{
    /**
     * Gets all Downloads items by parent item id.
     */
    public function getDownloadsItemsByParent($downloadsId, $itemId)
    {
        $items = array();
        $itemRows = $this->db()->select('*')
                ->from('downloads_items')
                ->where(array('downloads_id' => $downloadsId, 'parent_id' => $itemId))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new DownloadsItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemId);
            $itemModel->setDownloadsId($downloadsId);
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Gets all Downloads items by type.
     */
    public function getDownloadsCatItem($type)
    {
        $items = array();
        $itemRows = $this->db()->select('*')
                ->from('downloads_items')
                ->where(array('type' => $type))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new DownloadsItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemRow['parent_id']);
            $itemModel->setDownloadsId($itemRow['downloads_id']);
            $items[] = $itemModel;
        }

        return $items;
    }

    public function getDownloadsById($id)
    {
        $itemRows = $this->db()->select('*')
                ->from('downloads_items')
                ->where(array('id' => $id))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchAssoc();

        if (empty($itemRows)) {
            return null;
        }

        $itemModel = new DownloadsItem();
        $itemModel->setId($itemRows['id']);
        $itemModel->setType($itemRows['type']);
        $itemModel->setTitle($itemRows['title']);
        $itemModel->setDesc($itemRows['description']);
        $itemModel->setParentId($itemRows['parent_id']);
        $itemModel->setDownloadsId($itemRows['downloads_id']);

        return $itemModel;
    }
    

    /**
     * Save one Downloads item.
     *
     * @param  DownloadsItem $downloadsItem
     * @return integer
     */
    public function saveItem(DownloadsItem $downloadsItem)
    {
        $fields = array
        (
            'title' => $downloadsItem->getTitle(),
            'downloads_id' => $downloadsItem->getDownloadsId(),
            'sort' => $downloadsItem->getSort(),
            'parent_id' => $downloadsItem->getParentId(),
            'type' => $downloadsItem->getType(),
            'description' => $downloadsItem->getDesc(),
        );

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $itemId = (int)$this->db()->select('id')
            ->from('downloads_items')
            ->where(array('id' => $downloadsItem->getId()))
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('downloads_items')
                ->values($fields)
                ->where(array('id' => $itemId))
                ->execute();
        } else {
            $itemId = $this->db()->insert('downloads_items')
                ->values($fields)
                ->execute();
        }

        return $itemId;
    }
 
    /**
     * Delete the given Downloads item.
     *
     * @param  DownloadsItem $downloadsItem
     */
    public function deleteItem($downloadsItem)
    {
        $this->db()->delete('downloads_items')
            ->where(array('id' => $downloadsItem->getId()))
            ->execute();
    }

    /**
     * Gets all Downloads items by Downloads id.
     */
    public function getDownloadsItems($downloadsId)
    {
        $items = array();
        $itemRows = $this->db()->select('*')
                ->from('downloads_items')
                ->where(array('downloads_id' => $downloadsId))
                ->order(array('sort' => 'ASC'))
                ->execute()
                ->fetchRows();

        if (empty($itemRows)) {
            return null;
        }

        foreach ($itemRows as $itemRow) {
            $itemModel = new DownloadsItem();
            $itemModel->setId($itemRow['id']);
            $itemModel->setType($itemRow['type']);
            $itemModel->setTitle($itemRow['title']);
            $itemModel->setDesc($itemRow['description']);
            $itemModel->setParentId($itemRow['parent_id']);
            $itemModel->setDownloadsId($downloadsId);
            $items[] = $itemModel;
        }

        return $items;
    }
}
