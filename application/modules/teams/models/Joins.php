<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Teams\Models;

class Joins extends \Ilch\Model
{
    /**
     * The Id of the Join.
     *
     * @var int
     */
    protected $id;

    /**
     * The User Id from the User.
     *
     * @var int
     */
    protected $userId;

    /**
     * The Name from the User.
     *
     * @var string
     */
    protected $name;

    /**
     * The E-Mail from the User.
     *
     * @var string
     */
    protected $email;

    /**
     * The Gender from the User.
     *
     * @var int
     */
    protected $gender;

    /**
     * The Birthday from the User.
     *
     * @var string
     */
    protected $birthday;

    /**
     * The Place from the User.
     *
     * @var string
     */
    protected $place;

    /**
     * The Skill from the User.
     *
     * @var int
     */
    protected $skill;

    /**
     * The Team Id of the Join.
     *
     * @var int
     */
    protected $teamId;

    /**
     * The created Date of the Join.
     *
     * @var string
     */
    protected $dateCreated;

    /**
     * The Text of the Join.
     *
     * @var string
     */
    protected $text;

    /**
     * Sets the Id of the Join.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the Id of the Join.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the Id of the User.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets the Id of the User.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Gets the Name of the User.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the Name of the User.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the E-Mail of the User.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets the E-Mail of the User.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = (string)$email;

        return $this;
    }

    /**
     * Gets the Gender of the User.
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Sets the Gender of the User.
     *
     * @param int $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = (int)$gender;

        return $this;
    }

    /**
     * Gets the Birthday of the User.
     *
     * @return \Ilch\Date
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Sets the Birthday of the User.
     *
     * @param \Ilch\Date $birthday
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Gets the Place of the User.
     *
     * @return int
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the Place of the User.
     *
     * @param int $place
     * @return $this
     */
    public function setPlace($place)
    {
        $this->place = (string)$place;

        return $this;
    }

    /**
     * Gets the Skill of the User.
     *
     * @return int
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * Sets the Skill of the User.
     *
     * @param int $skill
     * @return $this
     */
    public function setSkill($skill)
    {
        $this->skill = (int)$skill;

        return $this;
    }

    /**
     * Gets the Team Id of the Join.
     *
     * @return int
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * Sets the Team Id of the Join.
     *
     * @param int $teamId
     * @return $this
     */
    public function setTeamId($teamId)
    {
        $this->teamId = (int)$teamId;

        return $this;
    }

    /**
     * Sets the created Date of the Join.
     *
     * @param \Ilch\Date $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Gets the created Date of the Join.
     *
     * @return \Ilch\Date
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Gets the Text of the Join.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the Text of the Join.
     *
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }
}
