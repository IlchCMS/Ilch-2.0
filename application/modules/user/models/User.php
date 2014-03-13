<?php
/**
 * Holds class User.
 *
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace User\Models;

defined('ACCESS') or die('no direct access');

/**
 * The user model class.
 *
 * @package ilch
 */
class User extends \Ilch\Model
{
    /**
     * The id of the user.
     *
     * @var int
     */
    protected $_id;

    /**
     * The username.
     *
     * @var string
     */
    protected $_name;

    /**
     * The email address of the user.
     *
     * @var string
     */
    protected $_email;

    /**
     * The password of the user.
     *
     * @var string
     */
    protected $_password;

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
     * LastActivity timestamp of the user.
     *
     * @var Ilch_Date
     */
    protected $_dateLastActivity;

    /**
     * Confirmed of the user.
     *
     * @var int
     */
    protected $_confirmed;

    /**
     * Confirmed Code of the user.
     *
     * @var string
     */
    protected $_confirmedCode;

    /**
     * The associated user group object.
     *
     * @var User\Models\Group[]
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
        $this->_id = (int) $id;

        return $this;
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
        $this->_name = (string) $username;

        return $this;
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
        $this->_email = (string) $email;

        return $this;
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
        $this->_password = (string) $password;

        return $this;
    }

    /**
     * Returns the confirmed of the user.
     *
     * @return int
     */
    public function getConfirmed()
    {
        return $this->_confirmed;
    }

    /**
     * Saves the confirmed of the user.
     *
     * @param int $confirmed
     */
    public function setConfirmed($confirmed)
    {
        $this->_confirmed = (int) $confirmed;

        return $this;
    }

    /**
     * Returns the confirmed code of the user.
     *
     * @return string
     */
    public function getConfirmedCode()
    {
        return $this->_confirmedCode;
    }

    /**
     * Saves the confirmed code of the user.
     *
     * @param string $confirmed
     */
    public function setConfirmedCode($confirmedCode)
    {
        $this->_confirmedCode = (string) $confirmedCode;

        return $this;
    }

    /**
     * Saves the groups of the user.
     *
     * @return Group[]
     */
    public function getGroups()
    {
        return $this->_groups;
    }

    /**
     * Sets the groups of the user.
     *
     * @param Group[] $groups
     */
    public function setGroups($groups)
    {
        $this->_groups = $groups;

        return $this;
    }

    /**
     * Adds a group to the users groups.
     *
     * @param Group $group
     */
    public function addGroup(Group $group)
    {
        if (!isset($this->_groups[$group->getId()])) {
            $this->_groups[$group->getId()] = $group;
        }

        return $this;
    }

    /**
     * Checks if user has the given group.
     *
     * @param integer $groupId
     * @return boolean
     */
    public function hasGroup($groupId)
    {
        if (!isset($this->_groups[$groupId])) {
            return false;
        }

        return true;
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
     * @param Ilch_Date $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->_dateCreated = $dateCreated;

        return $this;
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
     * @param Ilch_Date $dateConfirmed
     */
    public function setDateConfirmed($dateConfirmed)
    {
        $this->_dateConfirmed = $dateConfirmed;

        return $this;
    }

    /**
     * Returns the date_LastActivity timestamp of the user.
     *
     * @return Ilch_Date
     */
    public function getDateLastActivity()
    {
        return $this->_dateLastActivity;
    }

    /**
     * Saves the date_LastActivity timestamp of the user.
     *
     * @param \Ilch\Date $dateLastActivity
     */
    public function setDateLastActivity($dateLastActivity)
    {
        $this->_dateLastActivity = $dateLastActivity;

        return $this;
    }

    /**
     * Returns whether the user has access to a specific key.
     *
     * @param  string  $key       A module-key, page-id or article-id prefixed by either one of these: "module_", "page_", "article_".
     * @param  boolean $isInAdmin Whether the user is in the admin backend currently.
     *
     * @return boolean            True if access granted, false otherwise.
     */
    public function hasAccess($key, $isInAdmin = true)
    {
        if(in_array(1, array_keys($this->getGroups()))) {
            /*
             * The user is an admin, allow him everything.
             */
            return true;
        }

        $type = '';
        $rec = array();
        $sql = 'SELECT ga.access_level
                FROM [prefix]_groups_access AS ga';

        if (strpos($key, 'module_') !== false) {
            $moduleKey = substr($key, 7);
            $type = 'module';
            $sqlJoin = ' INNER JOIN [prefix]_modules AS m ON ga.module_id = m.id';
            $sqlWhere = ' WHERE m.key = "'.$moduleKey.'"';
        } elseif (strpos($key, 'page_') !== false) {
            $pageId = (int)substr($key, 5);
            $type = 'page';
            $sqlJoin = ' INNER JOIN [prefix]_pages AS p ON ga.page_id = p.id';
            $sqlWhere = ' WHERE p.id = '.(int)$pageId;
        } elseif (strpos($key, 'article_') !== false) {
            $articleId = (int)substr($key, 8);
            $type = 'article';
            $sqlJoin = ' INNER JOIN [prefix]_articles AS a ON ga.article_id = a.id';
            $sqlWhere = ' WHERE a.id = '.(int)$articleId;
        } elseif (strpos($key, 'box_') !== false) {
            $boxId = (int)substr($key, 4);
            $type = 'box';
            $sqlJoin = ' INNER JOIN [prefix]_boxes AS b ON ga.box_id = b.id';
            $sqlWhere = ' WHERE b.id = '.(int)$boxId;
        }

        $sql .= $sqlJoin.$sqlWhere.'
                AND ga.group_id IN ('.implode(',', array_keys($this->getGroups())).')
                ORDER BY access_level DESC
                LIMIT 1';
        $db = \Ilch\Registry::get('db');
        $accessLevel = (int)$db->queryCell($sql);

        if(($isInAdmin && $accessLevel === 2) || (!$isInAdmin && $accessLevel >= 1)) {
            return true;
        } else {
            return false;
        }
    }
}
