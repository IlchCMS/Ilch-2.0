<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Mappers;

use Ilch\Mapper;
use Modules\Downloads\Models\DownloadsItem;
use Modules\Downloads\Mappers\Access as AccessMapper;

class Downloads extends Mapper
{
    /**
     * Gets all Downloads items by Downloads id.
     *
     * @return array|null
     */
    public function getDownloadsItems(): ?array
    {
        $items = [];
        $itemRows = $this->db()->select(['di.id', 'di.type', 'di.title', 'di.description', 'di.parent_id'])
            ->from(['di' => 'downloads_items'])
            ->join(['da' => 'downloads_access'], 'da.item_id = di.id', 'LEFT', ['access' => 'GROUP_CONCAT(DISTINCT da.group_id)'])
            ->group(['di.id'])
            ->order(['sort' => 'ASC'])
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
            $itemModel->setAccess($itemRow['access'] ?? '');
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Gets all Downloads items by parent item id.
     *
     * @param int $itemId
     * @return array|null
     */
    public function getDownloadsItemsByParent(int $itemId): ?array
    {
        $items = [];
        $itemRows = $this->db()->select(['di.id', 'di.type', 'di.title', 'di.description'])
            ->from(['di' => 'downloads_items'])
            ->join(['da' => 'downloads_access'], 'da.item_id = di.id', 'LEFT', ['access' => 'GROUP_CONCAT(DISTINCT da.group_id)'])
            ->where(['parent_id' => $itemId])
            ->group(['di.id'])
            ->order(['sort' => 'ASC'])
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
            $itemModel->setAccess($itemRow['access'] ?? '');
            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Get download by id.
     *
     * @param int $id id of download
     * @return DownloadsItem|null
     */
    public function getDownloadsById(int $id): ?DownloadsItem
    {
        $itemRows = $this->db()->select(['di.id', 'di.type', 'di.title', 'di.description', 'di.parent_id'])
            ->from(['di' => 'downloads_items'])
            ->join(['da' => 'downloads_access'], 'da.item_id = di.id', 'LEFT', ['access' => 'GROUP_CONCAT(DISTINCT da.group_id)'])
            ->where(['id' => $id])
            ->order(['sort' => 'ASC'])
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
        $itemModel->setAccess($itemRow['access'] ?? '');

        return $itemModel;
    }

    /**
     * Save one Downloads item.
     *
     * @param DownloadsItem $downloadsItem
     * @return int
     */
    public function saveItem(DownloadsItem $downloadsItem): int
    {
        $fields = [
            'title' => $downloadsItem->getTitle(),
            'sort' => $downloadsItem->getSort(),
            'parent_id' => $downloadsItem->getParentId(),
            'type' => $downloadsItem->getType(),
            'description' => $downloadsItem->getDesc()
        ];

        foreach ($fields as $key => $value) {
            if ($value === null) {
                unset($fields[$key]);
            }
        }

        $itemId = (int)$this->db()->select('id')
            ->from('downloads_items')
            ->where(['id' => $downloadsItem->getId()])
            ->execute()
            ->fetchCell();

        if ($itemId) {
            $this->db()->update('downloads_items')
                ->values($fields)
                ->where(['id' => $itemId])
                ->execute();
        } else {
            $itemId = $this->db()->insert('downloads_items')
                ->values($fields)
                ->execute();
        }

        // Store access rights.
        $accessMapper = new AccessMapper();
        $accessMapper->save($itemId, $downloadsItem->getAccess());

        return $itemId;
    }

    /**
     * Delete the given Downloads item.
     *
     * @param DownloadsItem $downloadsItem
     */
    public function deleteItem(DownloadsItem $downloadsItem)
    {
        $this->db()->delete('downloads_items')
            ->where(['id' => $downloadsItem->getId()])
            ->execute();
    }
}
