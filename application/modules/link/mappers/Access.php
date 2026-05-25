<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Mappers;

use Ilch\Mapper;

/**
 * Mapper so handle the access rights for a category and link.
 *
 * @since 1.12.0
 */
class Access extends Mapper
{
    /**
     * @var string
     */
    public string $accessTable = 'link_access';

    /**
     * @var string
     */
    public string $accessLinksTable = 'link_links_access';

    /**
     * Save access rights for a category.
     *
     * @param int $itemId
     * @param string|null $access
     * @return void
     * @since 1.12.0
     */
    public function save(int $itemId, ?string $access)
    {
        $this->saveAccess($itemId, $access);
    }

    /**
     * Save access rights for a link.
     *
     * @param int $linksId
     * @param string|null $access
     * @return void
     * @since 1.12.0
     */
    public function saveLinksAccess(int $linksId, ?string $access)
    {
        $this->saveAccess($linksId, $access, true);
    }

    /**
     * Save access rights for a link or a category.
     *
     * @param int $id
     * @param string|null $access
     * @param bool $isLinkId
     * @return void
     * @since 1.12.0
     */
    private function saveAccess(int $id, ?string $access, bool $isLinkId = false)
    {
        if ($access === null) {
            return;
        }

        $columnName = ($isLinkId) ? 'link_id' : 'cat_id';
        $tableName = ($isLinkId) ? $this->accessLinksTable : $this->accessTable;

        $this->db()->delete($tableName)
            ->where([$columnName => $id])
            ->execute();

        $access = explode(',', $access);

        $preparedRows = [];
        foreach ($access as $groupId) {
            if ($groupId) {
                $preparedRows[] = [$id, $groupId];
            }
        }

        if (count($preparedRows)) {
            // Add access rights in chunks of 25 to the table.
            $chunks = array_chunk($preparedRows, 25);
            foreach ($chunks as $chunk) {
                $this->db()->insert($tableName)
                    ->columns([$columnName, 'group_id'])
                    ->values($chunk)
                    ->execute();
            }
        }
    }
}
