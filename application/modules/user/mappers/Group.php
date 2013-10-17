<?php
/**
 * Holds class Group.
 *
 * @author Jainta Martin
 * @copyright Ilch Pluto
 * @package ilch
 */

namespace User\Mappers;
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
	 * @param int $id
	 * @return false|GroupModel
	 */
	public function getById($id)
	{
		$where = array
		(
			'id' => (int)$id,
		);

		$groups = $this->_getBy($where);

		if(!empty($groups))
		{
			return reset($groups);
		}

		return null;
	}

	/**
	 * Returns user model found by the name or false if none found.
	 *
	 * @param string $name
	 * @return false|GroupModel
	 */
	public function getByName($name)
	{
		$where = array
		(
			'name' => $name,
		);

		$groups = $this->_getBy($where);

		if(!empty($groups))
		{
			return reset($groups);
		}

		return false;
	}

	/**
	 * Returns an array with user group models found by the where clause of false if
	 * none found.
	 *
	 * @param mixed[] $where
	 * @return false|GroupModel[]
	 */
	protected function _getBy($where = null)
	{
		$groupRows = $this->getDatabase()->selectArray
		(
			'*',
			'groups',
			$where
		);

		if(!empty($groupRows))
		{
			$groups = array_map(array($this, 'loadFromArray'), $groupRows);
			$groups = array_map(array($this, 'loadUsers'), $groups);

			return $groups;
		}

		return false;
	}

	/**
	 * Returns a user group created using an array with user group data.
	 *
	 * @param mixed[] $groupRow
	 * @return GroupModel
	 */
	public function loadFromArray($groupRow = array())
	{
		$group = new GroupModel();

		if(isset($groupRow['id']))
		{
			$group->setId($groupRow['id']);
		}

		if(isset($groupRow['name']))
		{
			$group->setName($groupRow['name']);
		}

		return $group;
	}

	/**
	 * Loads the user ids associated with a user group to the group model.
	 *
	 * @param  GroupModel $group
	 */
	public function loadUsers(GroupModel $group)
	{
		$users = $this->getDatabase()->selectList
		(
			'user_id',
			'users_groups',
			array('user_id' => $group->getId())
		);
		$group->setUsers($users);

		return $group;
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
		$password = $group->getPassword();
		$email = $group->getEmail();
		$dateCreated = $group->getDateCreated();
		$dateConfirmed = $group->getDateConfirmed();

		if(!empty($name))
		{
			$fields['name'] = $group->getName();
		}

		if(!empty($password))
		{
			$fields['password'] = $group->getPassword();
		}

		if(!empty($email))
		{
			$fields['email'] = $group->getEmail();
		}

		if(!empty($dateCreated))
		{
			$fields['date_created'] = $group->getDateCreated()->toDb();
		}

		if(!empty($dateConfirmed))
		{
			$fields['date_confirmed'] = $group->getDateConfirmed()->toDb();
		}

		$userId = $group->getId();

		if($userId && $this->getUserById($userId))
		{
			/*
			 * Group does exist already, update.
			 */
			$this->getDatabase()->update
			(
				$fields,
				'groups',
				array
				(
					'id' => $userId,
				)
			);
		}
		else
		{
			/*
			 * Group does not exist yet, insert.
			 */
			$userId = $this->getDatabase()->insert
			(
				$fields,
				'groups'
			);
		}

		if($group->getGroups())
		{
			$this->getDatabase()->delete
			(
				'users_groups',
				array('user_id' => $userId)
			);

			foreach($group->getGroups() as $groupId)
			{
				$this->getDatabase()->insert
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