<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Models;

use Ilch\Accesses;
use Ilch\Date;
use Ilch\Request;

class User extends \Ilch\Model
{
    /**
     * The id of the user.
     *
     * @var int|null
     */
    protected ?int $id = null;

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
     * @var string|null
     */
    protected ?string $firstname = null;

    /**
     * The lastname of the user.
     *
     * @var string|null
     */
    protected ?string $lastname = null;

    /**
     * The gender of the user.
     *
     * @var int
     */
    protected $gender;

    /**
     * The city of the user.
     *
     * @var string|null
     */
    protected ?string $city = null;

    /**
     * The \Ilch\Date of when the user got created.
     *
     * @var Date
     */
    protected $birthday;

    /**
     * The avatar of the user.
     *
     * @var string|null
     */
    protected ?string $avatar = null;

    /**
     * The signature of the user.
     *
     * @var string|null
     */
    protected ?string $signature = null;

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
     * @var int|null
     */
    protected ?int $opt_mail = null;

    /**
     * The opt_comments of the user.
     * Whether the user allows comments on the profile or not.
     *
     * @var int|null
     */
    protected ?int $opt_comments = null;

    /**
     * The admin_comments of the user.
     * Whether the admin allows comments on the profile of this user or not.
     * @var int|null
     */
    protected ?int $admin_comments = null;

    /**
     * The opt_gallery of the user.
     *
     * @var int|null
     */
    protected ?int $opt_gallery = null;

    /**
     * The \Ilch\Date of when the user got created.
     *
     * @var Date
     */
    protected $dateCreated;

    /**
     * The \Ilch\Date of when the user got confirmed.
     *
     * @var Date
     */
    protected $dateConfirmed;

    /**
     * LastActivity timestamp of the user.
     *
     * @var Date
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
     * @var Date
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
    protected int $locked = 0;

    /**
     * Selects Delete timestamp of the user.
     *
     * @var Date
     */
    protected $selectsdelete;

    /**
     * Returns the id of the user.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Saves the id of the user.
     *
     * @param int|null $id
     * @return User
     */
    public function setId(?int $id): User
    {
        $this->id = $id;

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
    public function setName(string $username): User
    {
        $this->name = $username;

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
    public function setEmail(string $email): User
    {
        $this->email = $email;

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
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the locale of the user.
     *
     * @return string|null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    /**
     * Saves the locale of the user.
     *
     * @param string $locale
     * @return User
     */
    public function setLocale(string $locale): User
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Returns the opt_mail of the user.
     *
     * @return int|null
     */
    public function getOptMail(): ?int
    {
        return $this->opt_mail;
    }

    /**
     * Saves the opt_mail of the user.
     *
     * @param int $opt_mail
     * @return User
     */
    public function setOptMail(int $opt_mail): User
    {
        $this->opt_mail = $opt_mail;

        return $this;
    }

    /**
     * Returns the opt_comments of the user.
     * Whether the user allows comments on the profile or not.
     *
     * @return int|null
     */
    public function getOptComments(): ?int
    {
        return $this->opt_comments;
    }

    /**
     * Sets the opt_comments of the user.
     * Whether the user allows comments on the profile or not.
     *
     * @param int $opt_comments
     * @return $this
     */
    public function setOptComments(int $opt_comments): User
    {
        $this->opt_comments = $opt_comments;
        return $this;
    }

    /**
     * Get admin_comments of the user.
     * Whether the admin allows comments on the profile of this user or not.
     *
     * @return int|null
     */
    public function getAdminComments(): ?int
    {
        return $this->admin_comments;
    }

    /**
     * Set admin_comments of the user.
     * Whether the admin allows comments on the profile of this user or not.
     *
     * @param int $admin_comments
     * @return $this
     */
    public function setAdminComments(int $admin_comments): User
    {
        $this->admin_comments = $admin_comments;
        return $this;
    }

    /**
     * Returns the opt_gallery of the user.
     *
     * @return int|null
     */
    public function getOptGallery(): ?int
    {
        return $this->opt_gallery;
    }

    /**
     * Saves the opt_gallery of the user.
     *
     * @param int $opt_gallery
     * @return User
     */
    public function setOptGallery(int $opt_gallery): User
    {
        $this->opt_gallery = $opt_gallery;

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
    public function setConfirmed(int $confirmed): User
    {
        $this->confirmed = $confirmed;

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
    public function setConfirmedCode(string $confirmedCode): User
    {
        $this->confirmedCode = $confirmedCode;

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
    public function setSelector(string $selector): User
    {
        $this->selector = $selector;

        return $this;
    }

    /**
     * Get date at which the confirm code expires.
     *
     * @return Date
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
    public function setGroups(array $groups): User
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
    public function hasGroup(int $groupId): bool
    {
        if (!isset($this->groups[$groupId])) {
            return false;
        }

        return true;
    }

    /**
     * Returns the date_created \Ilch\Date of the user.
     *
     * @return Date
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Saves the date_created \Ilch\Date of the user.
     *
     * @param Date $dateCreated
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
     * @return Date
     */
    public function getDateConfirmed()
    {
        return $this->dateConfirmed;
    }

    /**
     * Saves the date_confirmed timestamp of the user.
     *
     * @param Date $dateConfirmed
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
     * @return Date
     */
    public function getDateLastActivity()
    {
        return $this->dateLastActivity;
    }

    /**
     * Saves the date_LastActivity timestamp of the user.
     *
     * @param Date $dateLastActivity
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
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstname;
    }

    /**
     * Saves the firstname of the user.
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstName(string $firstname): User
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Returns the lastname of the user.
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    /**
     * Saves the lastname of the user.
     *
     * @param string $lastname
     * @return User
     */
    public function setLastName(string $lastname): User
    {
        $this->lastname = $lastname;

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
    public function setGender(int $gender): User
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Returns the avatar of the user.
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Saves the avatar of the user.
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar(string $avatar): User
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Returns the signature of the user.
     *
     * @return string|null
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * Saves the signature of the user.
     *
     * @param string $signature
     * @return User
     */
    public function setSignature(string $signature): User
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Returns the city of the user.
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * Saves the city of the user.
     *
     * @param string $city
     * @return User
     */
    public function setCity(string $city): User
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Returns the date_created \Ilch\Date of the user.
     *
     * @return Date
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
     * @param Date $birthday
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
    public function getLocked(): int
    {
        return $this->locked;
    }

    /**
     * Set if user is locked
     *
     * @param int $locked
     * @return User
     */
    public function setLocked(int $locked): User
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Returns the selectsdelete \Ilch\Date of the user.
     *
     * @return Date
     */
    public function getSelectsDelete()
    {
        return $this->selectsdelete;
    }

    /**
     * Saves the selectsdelete \Ilch\Date of the user.
     *
     * @param Date $selectsdelete
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
