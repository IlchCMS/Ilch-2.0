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
        $groupRows = $this->db()->selectArray('*')
            ->from('groups')
            ->where($where)
            ->execute();

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

        $groupId = (int)$this->db()->selectCell('id')
            ->from('groups')
            ->where(array('id' => $group->getId()))
            ->execute();

        if ($groupId) {
            /*
             * Group does exist already, update.
             */
            $this->db()->update('groups')
                ->fields($fields)
                ->where(array('id' => $groupId))
                ->execute();
        } else {
            /*
             * Group does not exist yet, insert.
             */
            $groupId = $this->db()->insert('groups')
                ->fields($fields)
                ->execute();
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
        $groupExists = (boolean)$this->db()->selectCell('COUNT(*)')
            ->from('groups')
            ->where(array('id' => (int)$groupId))
            ->execute();

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

        $this->db()->delete('users_groups')
            ->where(array('group_id' => $groupId))
            ->execute();

        return $this->db()->delete('groups')
            ->where(array('id' => $groupId))
            ->execute();
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
        $accessList['entries'] = array(
            'page' => array(),
            'module' => array(),
            'article' => array(),
            'box' => array(),
        );
        $accessTypes = array(
            'module',
            'page',
            'article',
            'box',
        );

        foreach($accessDbList as $accessDbListEntry) {
            if(empty($accessList['group_name'])) {
                /*
                 * Only on first entry.
                 */
                $accessList['group_name'] = $accessDbListEntry['group_name'];
            }

            foreach($accessTypes as $accessType) {
                if(!empty($accessDbListEntry[$accessType.'_id'])) {
                    $entryId = $accessDbListEntry[$accessType.'_id'];
                    $accessList['entries'][$accessType][$accessDbListEntry[$accessType.'_id']] = $accessDbListEntry['access_level'];
                    break;
                }
            }
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
        $entryExists = (bool)$this->db()->selectCell('COUNT(*)')
            ->from('groups_access')
            ->where($rec)
            ->execute();

        if($entryExists) {
            $this->db()->update('groups_access')
                ->fields($fields)
                ->where($rec)
                ->execute();
        } else {
            $this->db()->insert('groups_access')
                ->fields($fields)
                ->execute();
        }
    }
}
