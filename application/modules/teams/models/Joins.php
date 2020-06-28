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
     * The Locale of the Join.
     *
     * @var string
     */
    protected $locale;

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
     * The decision of the Join.
     *
     * @var int
     */
    protected $decision;

    /**
     * The value of undecided of the Join.
     *
     * @var int
     */
    protected $undecided;

    /**
     * Sets the Id of the Join.
     *
     * @param int $id
     * @return $this
     */
    public function setId($id): self
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the Id of the Join.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Gets the Id of the User.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the Id of the User.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId): self
    {
        $this->userId = (int)$userId;

        return $this;
    }

    /**
     * Gets the Name of the User.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the Name of the User.
     *
     * @param string $name
     * @return $this
     */
    public function setName($name): self
    {
        $this->name = (string)$name;

        return $this;
    }

    /**
     * Gets the E-Mail of the User.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Sets the E-Mail of the User.
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email): self
    {
        $this->email = (string)$email;

        return $this;
    }

    /**
     * Gets the Gender of the User.
     *
     * @return int
     */
    public function getGender(): int
    {
        return $this->gender;
    }

    /**
     * Sets the Gender of the User.
     *
     * @param int $gender
     * @return $this
     */
    public function setGender($gender): self
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
    public function setBirthday($birthday): self
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
    public function setPlace($place): self
    {
        $this->place = (string)$place;

        return $this;
    }

    /**
     * Gets the Skill of the User.
     *
     * @return int
     */
    public function getSkill(): int
    {
        return $this->skill;
    }

    /**
     * Sets the Skill of the User.
     *
     * @param int $skill
     * @return $this
     */
    public function setSkill($skill): self
    {
        $this->skill = (int)$skill;

        return $this;
    }

    /**
     * Gets the Team Id of the Join.
     *
     * @return int
     */
    public function getTeamId(): int
    {
        return $this->teamId;
    }

    /**
     * Sets the Team Id of the Join.
     *
     * @param int $teamId
     * @return $this
     */
    public function setTeamId($teamId): self
    {
        $this->teamId = (int)$teamId;

        return $this;
    }

    /**
     * Gets the Locale of the Join.
     *
     * @return string
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Sets the Locale of the Join.
     *
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale): self
    {
        $this->locale = (string)$locale;

        return $this;
    }

    /**
     * Sets the created Date of the Join.
     *
     * @param \Ilch\Date $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated): self
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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the Text of the Join.
     *
     * @param string $text
     * @return $this
     */
    public function setText($text): self
    {
        $this->text = (string)$text;

        return $this;
    }

    /**
     * Gets the status of the Join.
     * 1 = accepted, 2 = declined
     *
     * @return int
     */
    public function getDecision(): int
    {
        return $this->decision;
    }

    /**
     * Sets the status of the Join.
     *
     * @param int $decision
     * @return $this
     */
    public function setDecision($decision): self
    {
        $this->decision = $decision;

        return $this;
    }

    /**
     * Gets the value of undecided.
     *
     * @return int
     */
    public function getUndecided(): int
    {
        return $this->undecided;
    }

    /**
     * Sets the value of undecided.
     *
     * @param int $undecided
     * @return $this
     */
    public function setUndecided($undecided): self
    {
        $this->undecided = $undecided;

        return $this;
    }
}
