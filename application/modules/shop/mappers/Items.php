<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Item as ItemsModel;

class Items extends Mapper
{
    /**
     * Gets items.
     *
     * @param array $where
     * @return ItemsModel[]|[]
     */
    public function getShopItems(array $where = []): array
    {
        $itemsArray = $this->db()->select('*')
            ->from('shop_items')
            ->where($where)
            ->order(['name' => 'ASC'])
            ->execute()
            ->fetchRows();

        if (empty($itemsArray)) {
            return [];
        }

        $items = [];
        foreach ($itemsArray as $itemRow) {
            $itemModel = new ItemsModel();
            $itemModel->setId($itemRow['id']);
            $itemModel->setCode($itemRow['code']);
            $itemModel->setCatId($itemRow['cat_id']);
            $itemModel->setName($itemRow['name']);
            $itemModel->setItemnumber($itemRow['itemnumber']);
            $itemModel->setStock($itemRow['stock']);
            $itemModel->setUnitName($itemRow['unitName']);
            $itemModel->setCordon($itemRow['cordon']);
            $itemModel->setCordonText($itemRow['cordonText']);
            $itemModel->setCordonColor($itemRow['cordonColor']);
            $itemModel->setPrice($itemRow['price']);
            $itemModel->setTax($itemRow['tax']);
            $itemModel->setShippingCosts($itemRow['shippingCosts']);
            $itemModel->setShippingTime($itemRow['shippingTime']);
            $itemModel->setImage($itemRow['image']);
            $itemModel->setImage1($itemRow['image1']);
            $itemModel->setImage2($itemRow['image2']);
            $itemModel->setImage3($itemRow['image3']);
            $itemModel->setInfo($itemRow['info']);
            $itemModel->setDesc($itemRow['desc']);
            $itemModel->setStatus($itemRow['status']);

            $items[] = $itemModel;
        }

        return $items;
    }

    /**
     * Gets item by id.
     *
     * @param int $id
     * @return ItemsModel|false
     */
    public function getShopItemById(int $id)
    {
        $shopItem = $this->getShopItems(['id' => $id]);
        return reset($shopItem);
    }

    /**
     * Get count of all active items.
     *
     * @param int $status
     * @return int
     */
    public function getCountOfItems(int $status = 1): int
    {
        return (int)$this->db()->select('COUNT(*)')
            ->from('shop_items')
            ->where(['status' => $status])
            ->execute()
            ->fetchCell();
    }

    /**
     * Return count of items per category.
     *
     * @param array $catIds
     * @param int $status
     * @return array array[cat_id] => count
     */
    public function getCountOfItemsPerCategory(array $catIds = [], int $status = 1): array
    {
        if (empty($catIds)){
            return [];
        }
        $itemsArray = $this->db()->select(['cat_id', 'count' => 'COUNT(id)'])
            ->from('shop_items')
            ->where(['cat_id' => $catIds, 'status' => $status])
            ->group(['cat_id'])
            ->execute()
            ->fetchRows();
        
        return array_column($itemsArray, 'count', 'cat_id');
    }

    /**
     * Inserts or updates item model.
     *
     * @param ItemsModel $item
     * @return int
     */
    public function save(ItemsModel $item): int
    {
        $fields = [
            'code' => strtolower(preg_replace('/[^a-z0-9]/i', '', $item->getName())) . '_' . time(),
            'cat_id' => $item->getCatId(),
            'name' => $item->getName(),
            'itemnumber' => $item->getItemnumber(),
            'stock' => $item->getStock(),
            'unitName' => $item->getUnitName(),
            'cordon' => $item->getCordon(),
            'cordonText' => $item->getCordonText(),
            'cordonColor' => $item->getCordonColor(),
            'price' => $item->getPrice(),
            'tax' => $item->getTax(),
            'shippingCosts' => $item->getShippingCosts(),
            'shippingTime' => $item->getShippingTime(),
            'image' => $item->getImage(),
            'image1' => $item->getImage1(),
            'image2' => $item->getImage2(),
            'image3' => $item->getImage3(),
            'info' => $item->getInfo(),
            'desc' => $item->getDesc(),
            'status' => $item->getStatus(),
        ];

        if ($item->getId()) {
            $this->db()->update('shop_items')
                ->values($fields)
                ->where(['id' => $item->getId()])
                ->execute();
            return $item->getId();
        } else {
            return $this->db()->insert('shop_items')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Update item stock. Sets the stock to a specific quantity.
     *
     * @param int $id
     * @param int $newStock
     */
    public function updateStock(int $id, int $newStock)
    {
        $this->db()->update('shop_items')
            ->values(['stock' => $newStock])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Add stock. Increases the stock by a specific quantity.
     *
     * @param int $id
     * @param int $quantity
     * @return void
     */
    public function addStock(int $id, int $quantity)
    {
        $dbStock = $this->db()->select('stock')
            ->from('shop_items')
            ->where(['id' => $id])
            ->execute()
            ->fetchCell();

        $newStock = $dbStock + $quantity;

        $this->updateStock($id, $newStock);
    }

    /**
     * Remove stock. Decreases the stock by a specific quantity.
     *
     * @param int $id
     * @param int $quantity
     * @return void
     */
    public function removeStock(int $id, int $quantity)
    {
        $dbStock = $this->db()->select('stock')
            ->from('shop_items')
            ->where(['id' => $id])
            ->execute()
            ->fetchCell();

        $newStock = $dbStock - $quantity;

        $this->updateStock($id, $newStock);
    }

    /**
     * Deletes item with given id.
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->db()->delete('shop_items')
            ->where(['id' => $id])
            ->execute();
    }
}
