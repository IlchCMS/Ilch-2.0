<?php
/**
 * Holds class User_UserModel.
 *
 * @author Jainta Martin
 * @copyright Ilch Pluto
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * The user model class.
 *
 * @author Jainta Martin
 * @package ilch
 */
class User_UserModel extends Ilch_Model
{
	/**
	 * The id of the user.
	 *
	 * @var int
	 */
	protected $_id = null;

	/**
	 * The username.
	 *
	 * @var string
	 */
	protected $_name = '';

	/**
	 * The email address of the user.
	 *
	 * @var string
	 */
	protected $_email = '';

	/**
	 * The password of the user.
	 *
	 * @var string
	 */
	protected $_password = '';

	/**
	 * The Ilch_Date of when the user got created.
	 *
	 * @var Ilch_Date
	 */
	protected $_dateCreated;

	/**
	 * The Ilch_Date of when the user got confirmed.
	 *
	 * @var Ilch_Date
	 */
	protected $_dateConfirmed;

	/**
	 * The groups as id of the user.
	 *
	 * @var array
	 */
	protected $_groups = array();

	/**
	 * Returns the id of the user.
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->_id;
	}

	/**
	 * Saves the id of the user.
	 *
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->_id = (int)$id;
	}

	/**
	 * Returns the username.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * Saves the username.
	 *
	 * @param string $username
	 */
	public function setName($username)
	{
		$this->_name = (string)$username;
	}

	/**
	 * Returns the email address of the user.
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->_email;
	}

	/**
	 * Saves the email address of the user.
	 *
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->_email = (string)$email;
	}

	/**
	 * Returns the password of the user.
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->_password;
	}

	/**
	 * Saves the password of the user.
	 *
	 * @param string $password
	 */
	public function setPassword($password)
	{
		$this->_password = (string)$password;
	}

	/**
	 * Saves the groups of the user.
	 *
	 * @return array
	 */
	public function getGroups()
	{
		return $this->_groups;
	}

	/**
	 * Saves the groups of the user.
	 *
	 * @param array $groups
	 */
	public function setGroups($groups)
	{
		$this->_groups = (array)$groups;
	}

	/**
	 * Returns the date_created Ilch_Date of the user.
	 *
	 * @return Ilch_Date
	 */
	public function getDateCreated()
	{
		return $this->_dateCreated;
	}

	/**
	 * Saves the date_created Ilch_Date of the user.
	 *
	 * @param int|Ilch_Date|string $dateCreated
	 */
	public function setDateCreated($dateCreated)
	{
		if(is_numeric($dateCreated))
		{
			$timestamp = (int)$dateCreated;
			$dateCreated = new Ilch_Date();
			$dateCreated->SetTimestamp($timestamp);
		}
		elseif(is_string($dateCreated))
		{
			$dateCreated = new Ilch_Date($dateCreated);
		}
		elseif(!is_a($dateCreated, 'Ilch_Date'))
		{
			throw new InvalidArgumentException('DateCreated must be a timestamp, date-string or Ilch_Date, "'.$dateCreated.'" given.');
		}

		$this->_dateCreated = $dateCreated;
	}

	/**
	 * Returns the date_confirmed timestamp of the user.
	 *
	 * @return Ilch_Date
	 */
	public function getDateConfirmed()
	{
		return $this->_dateConfirmed;
	}

	/**
	 * Saves the date_confirmed timestamp of the user.
	 *
	 * @param int|Ilch_Date|string $dateConfirmed
	 */
	public function setDateConfirmed($dateConfirmed)
	{
		if(is_numeric($dateConfirmed))
		{
			$timestamp = (int)$dateConfirmed;
			$dateConfirmed = new Ilch_Date();
			$dateConfirmed->SetTimestamp($timestamp);
		}
		elseif(is_string($dateConfirmed))
		{
			$dateConfirmed = new Ilch_Date($dateConfirmed);
		}
		elseif(!is_a($dateConfirmed, 'Ilch_Date'))
		{
			throw new InvalidArgumentException('DateConfirmed must be a timestamp, date-string or Ilch_Date, "'.$dateConfirmed.'" given.');
		}

		$this->_dateConfirmed = $dateConfirmed;
	}
}