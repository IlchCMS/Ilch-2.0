<?php
/**
 * Holds class Group.
 *
 * @author Jainta Martin
 * @copyright Ilch Pluto
 * @package ilch
 */

namespace user\mappers;
use User\Models\Group as GroupModel;
defined('ACCESS') or die('no direct access');

/**
 * The user group mapper class.
 *
 * @author Jainta Martin
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
    public function getById($id)
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
    public function getByName($name)
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

        $groupId = $group->getId();

        if ($groupId && $this->getUserById($groupId)) {
            /*
             * Group does exist already, update.
             */
            $this->db()->update
            (
                $fields,
                'groups',
                array
                (
                    'group_id' => $groupId,
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

        if ($group->getUsers()) {
            $this->db()->delete
            (
                'users_groups',
                array('group_id' => $groupId)
            );

            foreach ($group->getUsers() as $userId) {
                $this->db()->insert
                (
                    array
                    (
                        'user_id' => $userId,
                        'group_id' => $groupId
                    ),
                    'users_groups'
                );
            }
        }
    }
}
