<?php
/**
 * Holds class Group.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Mappers;

use User\Models\Group as GroupModel;

defined('ACCESS') or die('no direct access');

/**
 * The user group mapper class.
 *
 * @package ilch
 */
class Group extends \Ilch\Mapper
{
    /**
     * Returns user model found by the id or false if none found.
     *
     * @param  int              $id
     * @return false|GroupModel
     */
    public function getGroupById($id)
    {
        $where = array
        (
            'id' => (int) $id,
        );

        $groups = $this->_getBy($where);

        if (!empty($groups)) {
            return reset($groups);
        }

        return null;
    }

    /**
     * Returns user model found by the name or false if none found.
     *
     * @param  string           $name
     * @return false|GroupModel
     */
    public function getGroupByName($name)
    {
        $where = array
        (
            'name' => $name,
        );

        $groups = $this->_getBy($where);

        if (!empty($groups)) {
            return reset($groups);
        }

        return false;
    }

    /**
     * Returns an array with user group models found by the where clause of false if
     * none found.
     *
     * @param  mixed[]            $where
     * @return false|GroupModel[]
     */
    protected function _getBy($where = null)
    {
        $groupRows = $this->db()->selectArray
        (
            '*',
            'groups',
            $where
        );

        if (!empty($groupRows)) {
            $groups = array_map(array($this, 'loadFromArray'), $groupRows);

            return $groups;
        }

        return false;
    }

    /**
     * Returns a user group created using an array with user group data.
     *
     * @param  mixed[]    $groupRow
     * @return GroupModel
     */
    public function loadFromArray($groupRow = array())
    {
        $group = new GroupModel();

        if (isset($groupRow['id'])) {
            $group->setId($groupRow['id']);
        }

        if (isset($groupRow['name'])) {
            $group->setName($groupRow['name']);
        }

        return $group;
    }

    /**
     * Loads the user ids associated with a user group.
     *
     * @param int $groupId
     */
    public function getUsersForGroup($groupId)
    {
        $userIds = $this->db()->selectList
        (
            'user_id',
            'users_groups',
            array('group_id' => $groupId)
        );

        return $userIds;
    }

    /**
     * Inserts or updates a user group model into the database.
     *
     * @param GroupModel $group
     */
    public function save(GroupModel $group)
    {
        $fields = array();
        $name = $group->getName();

        if (!empty($name)) {
            $fields['name'] = $group->getName();
        }

        $groupId = (int) $this->db()->selectCell
        (
            'id',
            'groups',
            array
            (
                'id' => $group->getId(),
            )
        );

        if ($groupId) {
            /*
             * Group does exist already, update.
             */
            $this->db()->update
            (
                $fields,
                'groups',
                array
                (
                    'id' => $groupId,
                )
            );
        } else {
            /*
             * Group does not exist yet, insert.
             */
            $groupId = $this->db()->insert
            (
                $fields,
                'groups'
            );
        }

        return $groupId;
    }

    /**
     * Returns a array of all group model objects.
     *
     * @return groupModel[]
     */
    public function getGroupList()
    {
        return $this->_getBy();
    }

    /**
     * Returns whether a group with the given id exists in the database.
     *
     * @param  int $groupId
     * @return boolean
     */
    public function groupWithIdExists($groupId)
    {
        $groupExists = (boolean)$this->db()->selectCell
        (
            'COUNT(*)',
            'groups',
            array
            (
                'id' => (int)$groupId
            )
        );

        return $groupExists;
    }

    /**
     * Deletes a given group or a user with the given id.
     *
     * @param  int|GroupModel $groupId
     *
     * @return boolean True of success, otherwise false.
     */
    public function delete($groupId)
    {
        if(is_a($groupId, '\User\Models\Group'))
        {
            $groupId = $groupId->getId();
        }

        $this->db()->delete('users_groups', array('group_id' => $groupId));
        return $this->db()->delete('groups', array('id' => $groupId));
    }

    /**
     * Returns the group access list from the database.
     *
     * @param  int     $groupId
     * @return mixed[]
     */
    public function getGroupAccessList($groupId)
    {
        $sql = 'SELECT g.name AS group_name, ga.*
                FROM [prefix]_groups_access AS ga
                INNER JOIN [prefix]_groups AS g ON ga.group_id = g.id
                WHERE ga.group_id = '.(int)$groupId;
        $accessDbList = $this->db()->queryArray($sql);
        $accessList = array();

        foreach($accessDbList as $accessDbListEntry) {
            if(empty($accessList)) {
                /*
                 * First entry.
                 */
                $accessList['group_name'] = $accessDbListEntry['group_name'];
                $accessList['entries'] = array(
                    'pages' => array(),
                    'modules' => array(),
                    'articles' => array(),
                );
            }

            if(!empty($accessDbListEntry['module_id'])) {
                $entryType = 'modules';
                $entryId = $accessDbListEntry['module_id'];
            } elseif(!empty($accessDbListEntry['page_id'])) {
                $entryType = 'pages';
                $entryId = $accessDbListEntry['page_id'];
            } elseif(!empty($accessDbListEntry['article_id'])) {
                $entryType = 'articles';
                $entryId = $accessDbListEntry['article_id'];
            }

            $accessList['entries'][$entryType][$entryId] = $accessDbListEntry['access_level'];
        }

        return $accessList;
    }

    /**
     * Saves or updates an access data entry to the db.
     *
     * @param  int    $groupId
     * @param  int    $typeId
     * @param  int    $accessLevel
     * @param  string $type
     */
    public function saveAccessData($groupId, $typeId, $accessLevel, $type)
    {
        $rec = array(
            'group_id' => (int)$groupId,
            $type.'_id' => (int)$typeId,
        );
        $fields = $rec;
        $fields['access_level'] = (int)$accessLevel;
        $entryExists = $this->db()->selectCell('COUNT(*)', 'groups_access', $rec);

        if($entryExists) {
            $this->db()->update($fields, 'groups_access', $rec);
        } else {
            $this->db()->insert($fields, 'groups_access');
        }
    }
}
