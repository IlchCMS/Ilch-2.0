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
     * @var string
     * @since 1.8.1
     */
    public $tablename = 'away_groups';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.8.1
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Get all groups that are subscribed to notifications.
     *
     * @return array|null
     */
    public function getGroups(): ?array
    {
        return $this->db()->select('*')
            ->from($this->tablename)
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
        $this->db()->truncate($this->tablename);
        $chunks = array_chunk($groups, 25);

        foreach ($chunks as $chunk) {
            $affectedRows += $this->db()->insert($this->tablename)
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
            $affectedRows += $this->db()->insert($this->tablename)
                ->columns(['group_id'])
                ->values($chunk)
                ->execute();
        }

        return $affectedRows;
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.8.1
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
