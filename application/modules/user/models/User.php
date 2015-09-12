<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Models;

defined('ACCESS') or die('no direct access');

class User extends \Ilch\Model
{
    /**
     * The id of the user.
     *
     * @var int
     */
    protected $id;

    /**
     * The username.
     *
     * @var string
     */
    protected $name;

    /**
     * The email address of the user.
     *
     * @var string
     */
    protected $email;

    /**
     * The email address of the user.
     *
     * @var string
     */
    protected $firstname;

    /**
     * The email address of the user.
     *
     * @var string
     */
    protected $lastname;

    /**
     * The email address of the user.
     *
     * @var string
     */
    protected $homepage;

    /**
     * The email address of the user.
     *
     * @var string
     */
    protected $city;

    /**
     * The \Ilch\Date of when the user got created.
     *
     * @var \Ilch\Date
     */
    protected $birthday;

    /**
     * The avatar of the user.
     *
     * @var string
     */
    protected $avatar;

    /**
     * The signature of the user.
     *
     * @var string
     */
    protected $signature;

    /**
     * The password of the user.
     *
     * @var string
     */
    protected $password;

    /**
     * The opt_mail of the user.
     *
     * @var int
     */
    protected $opt_mail;

    /**
     * The \Ilch\Date of when the user got created.
     *
     * @var \Ilch\Date
     */
    protected $dateCreated;

    /**
     * The \Ilch\Date of when the user got confirmed.
     *
     * @var \Ilch\Date
     */
    protected $dateConfirmed;

    /**
     * LastActivity timestamp of the user.
     *
     * @var \Ilch\Date
     */
    protected $dateLastActivity;

    /**
     * Confirmed of the user.
     *
     * @var int
     */
    protected $confirmed;

    /**
     * Confirmed Code of the user.
     *
     * @var string
     */
    protected $confirmedCode;

    /**
     * The associated user group object.
     *
     * @var \Modules\User\Models\Group[]
     */
    protected $groups = array();

    /**
     * Returns the id of the user.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Saves the id of the user.
     *
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Returns the username.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Saves the username.
     *
     * @param string $username
     * @return User
     */
    public function setName($username)
    {
        $this->name = (string)$username;

        return $this;
    }

    /**
     * Returns the email address of the user.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Saves the email address of the user.
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;

        return $this;
    }

    /**
     * Returns the password of the user.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Saves the password of the user.
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = (string)$password;

        return $this;
    }

    /**
     * Returns the opt_mail of the user.
     *
     * @return int
     */
    public function getOptMail()
    {
        return $this->opt_mail;
    }

    /**
     * Saves the opt_mail of the user.
     *
     * @param int $opt_mail
     * @return User
     */
    public function setOptMail($opt_mail)
    {
        $this->opt_mail = (string)$opt_mail;

        return $this;
    }

    /**
     * Returns the confirmed of the user.
     *
     * @return int
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Saves the confirmed of the user.
     *
     * @param int $confirmed
     * @return User
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = (int)$confirmed;

        return $this;
    }

    /**
     * Returns the confirmed code of the user.
     *
     * @return string
     */
    public function getConfirmedCode()
    {
        return $this->confirmedCode;
    }

    /**
     * Saves the confirmed code of the user.
     *
     * @param string $confirmedCode
     * @return User
     */
    public function setConfirmedCode($confirmedCode)
    {
        $this->confirmedCode = (string)$confirmedCode;

        return $this;
    }

    /**
     * Saves the groups of the user.
     *
     * @return Group[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Sets the groups of the user.
     *
     * @param Group[] $groups
     * @return User
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Adds a group to the users groups.
     *
     * @param Group $group
     * @return User
     */
    public function addGroup(Group $group)
    {
        if (!isset($this->groups[$group->getId()])) {
            $this->groups[$group->getId()] = $group;
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
        if (!isset($this->groups[$groupId])) {
            return false;
        }

        return true;
    }

    /**
     * Returns the date_created \Ilch\Date of the user.
     *
     * @return \Ilch\Date
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Saves the date_created \Ilch\Date of the user.
     *
     * @param \Ilch\Date $dateCreated
     * @return User
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Returns the date_confirmed timestamp of the user.
     *
     * @return \Ilch\Date
     */
    public function getDateConfirmed()
    {
        return $this->dateConfirmed;
    }

    /**
     * Saves the date_confirmed timestamp of the user.
     *
     * @param \Ilch\Date $dateConfirmed
     */
    public function setDateConfirmed($dateConfirmed)
    {
        $this->dateConfirmed = $dateConfirmed;

        return $this;
    }

    /**
     * Returns the date_LastActivity timestamp of the user.
     *
     * @return \Ilch\Date
     */
    public function getDateLastActivity()
    {
        return $this->dateLastActivity;
    }

    /**
     * Saves the date_LastActivity timestamp of the user.
     *
     * @param \Ilch\Date $dateLastActivity
     * @return User
     */
    public function setDateLastActivity($dateLastActivity)
    {
        $this->dateLastActivity = $dateLastActivity;

        return $this;
    }

    /**
     * Returns the email address of the user.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * Saves the email address of the user.
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstName($firstname)
    {
        $this->firstname = (string)$firstname;

        return $this;
    }

    /**
     * Returns the email address of the user.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * Saves the email address of the user.
     *
     * @param string $lastname
     * @return User
     */
    public function setLastName($lastname)
    {
        $this->lastname = (string)$lastname;

        return $this;
    }

    /**
     * Returns the email address of the user.
     *
     * @return string
     */
    public function getHomepage()
    {
        return $this->homepage;
    }

    /**
     * Saves the email address of the user.
     *
     * @param string $homepage
     * @return User
     */
    public function setHomepage($homepage)
    {
        $this->homepage = (string)$homepage;

        return $this;
    }

    /**
     * Returns the avatar of the user.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Saves the avatar of the user.
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = (string)$avatar;

        return $this;
    }

    /**
     * Returns the signature of the user.
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Saves the signature of the user.
     *
     * @param string $signature
     * @return User
     */
    public function setSignature($signature)
    {
        $this->signature = (string)$signature;

        return $this;
    }

    /**
     * Returns the city of the user.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Saves the city of the user.
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = (string)$city;

        return $this;
    }

    /**
     * Returns the date_created \Ilch\Date of the user.
     *
     * @return \Ilch\Date
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Saves the date_created \Ilch\Date of the user.
     *
     * @param \Ilch\Date $birthday
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Checks if user has admin group.
     *
     * @return boolean
     */
    public function isAdmin()
    {
        if (in_array(1, array_keys($this->getGroups()))) {
            return true;
        }

        return false;
    }

    /**
     * Returns whether the user has access to a specific key.
     *
     * @todo Remove from user model and create acl class
     * @param  string $key A module-key, page-id or article-id prefixed by either one of these: "module_", "page_", "article_".
     * @param  boolean $isInAdmin Whether the user is in the admin backend currently.
     *
     * @return boolean            True if access granted, false otherwise.
     *
     * @todo refactor -> kein Abhängigkeiten zu anderen Klassen, die keine Models sind
     */
    public function hasAccess($key, $isInAdmin = true)
    {
        if (in_array(1, array_keys($this->getGroups()))) {
            /*
             * The user is an admin, allow him everything.
             */
            return true;
        }

        $type = '';
        $sql = 'SELECT ga.access_level
                FROM [prefix]_groups_access AS ga';

        if (strpos($key, 'module_') !== false) {
            $moduleKey = substr($key, 7);
            $type = 'module';
            $sqlJoin = ' INNER JOIN `[prefix]_modules` AS m ON ga.module_key = m.key';
            $sqlWhere = ' WHERE m.key = "' . $moduleKey . '"';
        } elseif (strpos($key, 'page_') !== false) {
            $pageId = (int)substr($key, 5);
            $type = 'page';
            $sqlJoin = ' INNER JOIN `[prefix]_pages` AS p ON ga.page_id = p.id';
            $sqlWhere = ' WHERE p.id = ' . (int)$pageId;
        } elseif (strpos($key, 'article_') !== false) {
            $articleId = (int)substr($key, 8);
            $type = 'article';
            $sqlJoin = ' INNER JOIN [prefix]_articles AS a ON ga.article_id = a.id';
            $sqlWhere = ' WHERE a.id = ' . (int)$articleId;
        } elseif (strpos($key, 'box_') !== false) {
            $boxId = (int)substr($key, 4);
            $type = 'box';
            $sqlJoin = ' INNER JOIN [prefix]_boxes AS b ON ga.box_id = b.id';
            $sqlWhere = ' WHERE b.id = ' . (int)$boxId;
        }

        $sql .= $sqlJoin . $sqlWhere . '
                AND ga.group_id IN (' . implode(',', array_keys($this->getGroups())) . ')
                ORDER BY access_level DESC
                LIMIT 1';
        $db = \Ilch\Registry::get('db');
        $accessLevel = (int)$db->queryCell($sql);

        if (($isInAdmin && $accessLevel === 2) || (!$isInAdmin && $accessLevel >= 1)) {
            return true;
        } else {
            return false;
        }
    }
}
