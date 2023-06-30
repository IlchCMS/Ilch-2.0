<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

use Ilch\Accesses;
use Ilch\Request;

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
     * The firstname of the user.
     *
     * @var string
     */
    protected $firstname;

    /**
     * The lastname of the user.
     *
     * @var string
     */
    protected $lastname;

    /**
     * The gender of the user.
     *
     * @var int
     */
    protected $gender;

    /**
     * The city of the user.
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
     * The locale of the user.
     *
     * @var string
     */
    protected $locale;

    /**
     * The opt_mail of the user.
     *
     * @var int
     */
    protected $opt_mail;

    /**
     * The opt_gallery of the user.
     *
     * @var int
     */
    protected $opt_gallery;
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
     * selector of the user.
     *
     * @var string
     */
    protected $selector;

    /**
     * date at which the confirmCode expires.
     *
     * @var \Ilch\Date
     */
    protected $expires;

    /**
     * The associated user group object.
     *
     * @var Group[]
     */
    protected $groups = [];

    /**
     * Indicates if user is locked.
     *
     * @var int
     */
    protected $locked;

    /**
     * Selects Delete timestamp of the user.
     *
     * @var \Ilch\Date
     */
    protected $selectsdelete;

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
    public function setId($id): User
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
    public function setName($username): User
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
    public function setEmail($email): User
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
    public function setPassword($password): User
    {
        $this->password = (string)$password;

        return $this;
    }

    /**
     * Returns the locale of the user.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Saves the locale of the user.
     *
     * @param string $locale
     * @return User
     */
    public function setLocale($locale): User
    {
        $this->locale = (string)$locale;

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
    public function setOptMail($opt_mail): User
    {
        $this->opt_mail = (string)$opt_mail;

        return $this;
    }

    /**
     * Returns the opt_gallery of the user.
     *
     * @return int
     */
    public function getOptGallery()
    {
        return $this->opt_gallery;
    }

    /**
     * Saves the opt_gallery of the user.
     *
     * @param int $opt_gallery
     * @return User
     */
    public function setOptGallery($opt_gallery): User
    {
        $this->opt_gallery = (string)$opt_gallery;

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
    public function setConfirmed($confirmed): User
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
    public function setConfirmedCode($confirmedCode): User
    {
        $this->confirmedCode = (string)$confirmedCode;

        return $this;
    }

    /**
     * Returns the selector of the user.
     *
     * @return string
     */
    public function getSelector()
    {
        return $this->selector;
    }

    /**
     * Saves the selector of the user.
     *
     * @param string $selector
     * @return User
     */
    public function setSelector($selector): User
    {
        $this->selector = (string)$selector;

        return $this;
    }

    /**
     * Get date at which the confirm code expires.
     *
     * @return \Ilch\Date
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Set date at which the confirm code expires.
     *
     * @param $expires
     * @return User
     */
    public function setExpires($expires): User
    {
        $this->expires = $expires;

        return $this;
    }

    /**
     * Gets the groups of the user.
     *
     * @return Group[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * Sets the groups of the user.
     *
     * @param Group[] $groups
     * @return User
     */
    public function setGroups($groups): User
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
    public function addGroup(Group $group): User
    {
        if (!isset($this->groups[$group->getId()])) {
            $this->groups[$group->getId()] = $group;
        }

        return $this;
    }

    /**
     * Checks if user has the given group.
     *
     * @param int $groupId
     * @return bool
     */
    public function hasGroup($groupId): bool
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
    public function setDateCreated($dateCreated): User
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
     * @return User
     */
    public function setDateConfirmed($dateConfirmed): User
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
    public function setDateLastActivity($dateLastActivity): User
    {
        $this->dateLastActivity = $dateLastActivity;

        return $this;
    }

    /**
     * Returns the firstname of the user.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * Saves the firstname of the user.
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstName($firstname): User
    {
        $this->firstname = (string)$firstname;

        return $this;
    }

    /**
     * Returns the lastname of the user.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * Saves the lastname of the user.
     *
     * @param string $lastname
     * @return User
     */
    public function setLastName($lastname): User
    {
        $this->lastname = (string)$lastname;

        return $this;
    }

    /**
     * Returns the gender of the user.
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Saves the gender of the user.
     *
     * @param int $gender
     * @return User
     */
    public function setGender($gender): User
    {
        $this->gender = (int)$gender;

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
    public function setAvatar($avatar): User
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
    public function setSignature($signature): User
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
    public function setCity($city): User
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
        if ($this->birthday == '0000-00-00') {
            return '';
        }
        return $this->birthday;
    }

    /**
     * Saves the date_created \Ilch\Date of the user.
     *
     * @param \Ilch\Date $birthday
     * @return User
     */
    public function setBirthday($birthday): User
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get if user is locked.
     *
     * @return int
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set if user is locked
     *
     * @param mixed $locked
     * @return User
     */
    public function setLocked($locked): User
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Returns the selectsdelete \Ilch\Date of the user.
     *
     * @return \Ilch\Date
     */
    public function getSelectsDelete()
    {
        return $this->selectsdelete;
    }

    /**
     * Saves the selectsdelete \Ilch\Date of the user.
     *
     * @param \Ilch\Date $selectsdelete
     * @return User
     */
    public function setSelectsDelete($selectsdelete): User
    {
        $this->selectsdelete = $selectsdelete;
        return $this;
    }

    /**
     * Checks if user has admin group.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        if (\array_key_exists(1, $this->getGroups())) {
            return true;
        }

        return false;
    }

    /**
     * Returns whether the user has access to a specific key.
     *
     * @param string $key A module-key, page-id or article-id prefixed by either one of these: "module_", "page_", "article_".
     * @param  bool $isInAdmin Whether the user is in the admin backend currently.
     *
     * @return bool            True if access granted, false otherwise.
     *
     * @todo Remove from user model and create acl class
     * @todo refactor -> kein Abhängigkeiten zu anderen Klassen, die keine Models sind
     */
    public function hasAccess(string $key, bool $isInAdmin = true): bool
    {
        $findSub = strpos($key, '_');
        if ($findSub !== false) {
            $keyParts = explode('_', $key);
            $key = $keyParts[1];
            $type = $keyParts[0];
        } else {
            $type = 'module';
        }

        $accesses = new Accesses(new Request(false));
        $accesses->setUser($this);

        if ($type == 'page') {
            $type = $accesses::TYPE_PAGE;
        } elseif ($type == 'box') {
            $type = $accesses::TYPE_BOX;
        } elseif ($type == 'article') {
            $type = $accesses::TYPE_ARTICLE;
        } else {
            $type = $accesses::TYPE_MODULE;
        }

        return $accesses->hasAccess($isInAdmin ? 'Admin' : 'Module', $key, $type);
    }
}
