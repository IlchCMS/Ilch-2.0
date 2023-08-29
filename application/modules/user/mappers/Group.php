<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\Group as GroupModel;
use Modules\User\Models\User;

class Group extends \Ilch\Mapper
{
    /**
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 2.1.51
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists('groups') && $this->db()->ifTableExists('users_groups');
    }

    /**
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return GroupModel[]|null
     * @since 2.1.51
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['a.id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select()
            ->fields(['a.id', 'a.name'])
            ->from(['a' => 'groups'])
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entries = [];

        foreach ($entryArray as $rows) {
            $entryModel = new GroupModel();

            $entryModel->setByArray($rows);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Returns user model found by the id or false if none found.
     *
     * @param int $id
     * @return null|GroupModel
     */
    public function getGroupById(int $id): ?GroupModel
    {
        $entries = $this->getEntriesBy(['id' => $id]);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Returns user model found by the name or false if none found.
     *
     * @param string $name
     * @return null|GroupModel
     */
    public function getGroupByName(string $name): ?GroupModel
    {
        $entries = $this->getEntriesBy(['name' => $name]);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Returns an array with user group models found by the where clause of false if
     * none found.
     *
     * @param array $where
     * @return GroupModel[]|null
     */
    protected function getBy(array $where = []): ?array
    {
        return $this->getEntriesBy($where);
    }
    /**
     * Returns a user group created using an array with user group data.
     *
     * @param array $groupRow
     * @return GroupModel
     */
    public function loadFromArray(array $groupRow = []): GroupModel
    {
        $group = new GroupModel();
        $group->setByArray($groupRow);

        return $group;
    }

    /**
     * Loads the user ids associated with a user group.
     *
     * @param int $groupId
     * @return string[]
     */
    public function getUsersForGroup(int $groupId): array
    {
        return $this->db()->select('user_id', 'users_groups', ['group_id' => $groupId])
            ->execute()
            ->fetchList();
    }

    /**
     * Inserts or updates a user group model into the database.
     *
     * @param GroupModel $group
     * @return int
     */
    public function save(GroupModel $group): int
    {
        $fields = $group->getArray(false);

        if ($group->getId()) {
            $this->db()->update('groups')
                ->values($fields)
                ->where(['id' => $group->getId()])
                ->execute();

            return $group->getId();
        } else {
            return $this->db()->insert('groups')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Returns an array of all group model objects.
     *
     * @return groupModel[]|null
     */
    public function getGroupList(): ?array
    {
        return $this->getEntriesBy();
    }

    /**
     * Returns whether a group with the given id exists in the database.
     *
     * @param int $groupId
     * @return bool
     */
    public function groupWithIdExists(int $groupId): bool
    {
        return (bool) $this->db()->select('COUNT(*)', 'groups', ['id' => $groupId])
            ->execute()
            ->fetchCell();
    }

    /**
     * Deletes a given group or a user with the given id.
     *
     * @param  int|GroupModel $groupId
     *
     * @return bool True of success, otherwise false.
     */
    public function delete($groupId): bool
    {
        if (is_a($groupId, GroupModel::class)) {
            $groupId = $groupId->getId();
        }

        $this->db()->delete('users_groups')
            ->where(['group_id' => $groupId])
            ->execute();

        $this->db()->delete('groups_access')
            ->where(['group_id' => $groupId])
            ->execute();

        return $this->db()->delete('groups')
            ->where(['id' => $groupId])
            ->execute();
    }

    /**
     * Returns the group access list from the database.
     *
     * @param int $groupId
     * @return array
     */
    public function getGroupAccessList(int $groupId): array
    {
        $select = $this->db()->select()
            ->fields(['group_name' => 'g.name', 'ga.group_id', 'ga.access_level', 'ga.module_key', 'ga.page_id', 'ga.article_id', 'ga.box_id'])
            ->from(['ga' => 'groups_access'])
            ->join(['g' => 'groups'], 'ga.group_id = g.id', 'INNER')
            ->where(['ga.group_id' => $groupId]);

        $accessDbList = $select->execute()->fetchRows();
        $accessList = [];
        $accessList['entries'] = [
            'page' => [],
            'module' => [],
            'article' => [],
            'box' => [],
        ];
        $accessTypes = [
            'module',
            'page',
            'article',
            'box',
        ];

        foreach ($accessDbList as $accessDbListEntry) {
            if (empty($accessList['group_name'])) {
                /*
                 * Only on first entry.
                 */
                $accessList['group_name'] = $accessDbListEntry['group_name'];
            }

            foreach ($accessTypes as $accessType) {
                if ($accessType === 'module' && !empty($accessDbListEntry[$accessType . '_key']) && !is_numeric($accessDbListEntry[$accessType . '_key'])) {
                    $accessList['entries'][$accessType][$accessDbListEntry[$accessType . '_key']] = $accessDbListEntry['access_level'];
                    break;
                } elseif (!empty($accessDbListEntry[$accessType . '_id'])) {
                    $accessList['entries'][$accessType][$accessDbListEntry[$accessType . '_id']] = $accessDbListEntry['access_level'];
                    break;
                }
            }
        }

        return $accessList;
    }

    /**
     * Returns the access list from the database.
     *
     * @param string $type
     * @param string|int $id
     * @return null|array
     */
    public function getAccessAccessList(string $type, $id): ?array
    {
        if (!in_array($type, ['module', 'page', 'article', 'box'])) {
            return null;
        }

        $where = [];
        if ($type === 'module') {
            $where['ga.module_key'] = $this->db()->escape($id);
        } elseif ($type === 'page') {
            $where['ga.page_id'] = (int)$id;
        } elseif ($type === 'article') {
            $where['ga.article_id'] = (int)$id;
        } elseif ($type === 'box') {
            $where['ga.box_id'] = (int)$id;
        }

        $select = $this->db()->select()
            ->fields(['group_name' => 'g.name', 'ga.group_id', 'ga.access_level'])
            ->from(['ga' => 'groups_access'])
            ->join(['g' => 'groups'], 'ga.group_id = g.id', 'INNER')
            ->where($where);

        $accessDbList = $select->execute()->fetchRows();
        $accessList = [];
        $accessList['entries'] = [];

        $accessList['Type'] = $type;
        $accessList['Id'] = $id;

        foreach ($accessDbList as $accessDbListEntry) {
            $accessList['entries'][$accessDbListEntry['group_id']] = (int)$accessDbListEntry['access_level'];
        }
        return $accessList;
    }

    /**
     * Saves or updates an access data entry to the db.
     *
     * @param int $groupId
     * @param  string|int  $value
     * @param  int    $accessLevel
     * @param string $type
     * @return bool
     */
    public function saveAccessData(int $groupId, $value, int $accessLevel, string $type): bool
    {
        $rec = [
            'group_id' => $groupId,
        ];

        if ($type === 'module') {
            $rec['module_key'] = $value;
        } else {
            $rec[$type . '_id'] = (int)$value;
        }

        $fields = $rec;
        $fields['access_level'] = $accessLevel;
        $entryExists = (bool)$this->db()->select('COUNT(*)')
            ->from('groups_access')
            ->where($rec)
            ->execute()
            ->fetchCell();

        if ($entryExists) {
            return $this->db()->update('groups_access')
                ->values($fields)
                ->where($rec)
                ->execute();
        } else {
            return $this->db()->insert('groups_access')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Gets the AccessLevel by given Param
     *
     * @param string $key A module-key, page-id or article-id prefixed by either one of these: "module_", "page_", "article_", "box_".
     * @param User $user
     * @param int $default
     * @return int
     * @since 2.1.51
     */
    public function getAccessLevel(string $key, User $user, int $default = 1): int
    {
        if ($user->isAdmin()) {
            /*
             * The user is an admin, allow him everything.
             */
            return 2;
        }

        $select = $this->db()->select()
            ->fields(['ga.access_level'])
            ->from(['ga' => 'groups_access'])
            ->order(['ga.access_level' => 'DESC'])
            ->limit(1);

        $readAccess = [];
        foreach ($user->getGroups() as $us) {
            $readAccess[] = $us->getId();
        }
        $where = ['ga.group_id' => $readAccess];

        if (strpos($key, 'module_') !== false) {
            $moduleKey = substr($key, 7);
            $select->join(['m' => 'modules'], 'ga.module_key = m.key', 'INNER');
            $where['m.key'] = $this->db()->escape($moduleKey);
        } elseif (strpos($key, 'page_') !== false) {
            $pageId = (int)substr($key, 5);
            $select->join(['p' => 'pages'], 'ga.page_id = p.id', 'INNER');
            $where['p.id'] = $pageId;
        } elseif (strpos($key, 'article_') !== false) {
            $articleId = (int)substr($key, 8);
            $select->join(['a' => 'articles'], 'ga.article_id = a.id', 'INNER');
            $where['a.id'] = $articleId;
        } elseif (strpos($key, 'box_') !== false) {
            $boxId = (int)substr($key, 4);
            $select->join(['b' => 'boxes'], 'ga.box_id = b.id', 'INNER');
            $where['b.id'] = $boxId;
        }
        $select->where($where);
        $accessLevel = $select->execute()->fetchCell();

        if (!is_numeric($accessLevel)) {
            $accessLevel = $default;
        }

        return (int)$accessLevel;
    }
}
