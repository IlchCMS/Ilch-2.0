<?php
/**
 * @copyright Ilch 2
 * @package ilch
 * @since 1.5.0
 */

namespace Modules\Away\Mappers;

class Groups extends \Ilch\Mapper
{
    /**
     * Get all groups that are subscribed to notifications.
     *
     * @return array|null
     */
    public function getGroups()
    {
        return $this->db()->select('*')
            ->from('away_groups')
            ->execute()
            ->fetchList();
    }

    /**
     * Save groups to be notified in case of a new entry or changes to it.
     * Replaces current groups with the ones provided.
     *
     * @param array $groups
     * @return int
     */
    public function save(array $groups): int
    {
        $affectedRows = 0;
        $this->db()->truncate('away_groups');
        $chunks = array_chunk($groups, 25);

        foreach ($chunks as $chunk) {
            $affectedRows += $this->db()->insert('away_groups')
                ->columns(['group_id'])
                ->values($chunk)
                ->execute();
        }

        return $affectedRows;
    }

    /**
     * Add new groups to be notified.
     * Only adds new groups.
     *
     * @param array $groups
     * @return int
     */
    public function addGroups(array $groups): int
    {
        $affectedRows = 0;
        $existingGroups = $this->getGroups();
        $groupsToAdd = array_diff($groups, $existingGroups);
        $chunks = array_chunk($groupsToAdd, 25);

        foreach ($chunks as $chunk) {
            $affectedRows += $this->db()->insert('away_groups')
                ->columns(['group_id'])
                ->values($chunk)
                ->execute();
        }

        return $affectedRows;
    }
}
