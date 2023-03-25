<?php
/**
 * @copyright Ilch 2
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
    protected $id = 0;

    /**
     * The User Id from the User.
     *
     * @var int
     */
    protected $userId = 0;

    /**
     * The Name from the User.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The E-Mail from the User.
     *
     * @var string
     */
    protected $email = '';

    /**
     * The Gender from the User.
     *
     * @var int
     */
    protected $gender = 0;

    /**
     * The Birthday from the User.
     *
     * @var string
     */
    protected $birthday = '';

    /**
     * The Place from the User.
     *
     * @var string
     */
    protected $place = '';

    /**
     * The Skill from the User.
     *
     * @var int
     */
    protected $skill = 0;

    /**
     * The Team Id of the Join.
     *
     * @var int
     */
    protected $teamId = 0;

    /**
     * The Locale of the Join.
     *
     * @var string
     */
    protected $locale = '';

    /**
     * The created Date of the Join.
     *
     * @var string
     */
    protected $dateCreated = '';

    /**
     * The Text of the Join.
     *
     * @var string
     */
    protected $text = '';

    /**
     * The decision of the Join.
     *
     * @var int
     */
    protected $decision = 0;

    /**
     * The value of undecided of the Join.
     *
     * @var int
     */
    protected $undecided = 0;

    /**
     * Set this Model by Array
     *
     * @param array $entries
     * @return $this
     * @since 1.22.0
     */
    public function setByArray(array $entries): Joins
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['userId'])) {
            $this->setUserId($entries['userId']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['email'])) {
            $this->setEmail($entries['email']);
        }
        if (isset($entries['gender'])) {
            $this->setGender($entries['gender']);
        }
        if (isset($entries['birthday'])) {
            $this->setBirthday($entries['birthday']);
        }
        if (isset($entries['place'])) {
            $this->setPlace($entries['place']);
        }
        if (isset($entries['skill'])) {
            $this->setSkill($entries['skill']);
        }
        if (isset($entries['teamId'])) {
            $this->setTeamId($entries['teamId']);
        }
        if (isset($entries['locale'])) {
            $this->setLocale($entries['locale']);
        }
        if (isset($entries['dateCreated'])) {
            $this->setDateCreated($entries['dateCreated']);
        }
        if (isset($entries['text'])) {
            $this->setText($entries['text']);
        }
        if (isset($entries['decision'])) {
            $this->setDecision($entries['decision']);
        }
        if (isset($entries['undecided'])) {
            $this->setUndecided($entries['undecided']);
        }

        return $this;
    }

    /**
     * Sets the Id of the Join.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Joins
    {
        $this->id = $id;

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
    public function setUserId(int $userId): Joins
    {
        $this->userId = $userId;

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
    public function setName(string $name): Joins
    {
        $this->name = $name;

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
    public function setEmail(string $email): Joins
    {
        $this->email = $email;

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
    public function setGender(int $gender): Joins
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Gets the Birthday of the User.
     *
     * @return string
     */
    public function getBirthday(): string
    {
        return $this->birthday;
    }

    /**
     * Sets the Birthday of the User.
     *
     * @param string $birthday
     * @return $this
     */
    public function setBirthday(string $birthday): Joins
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Gets the Place of the User.
     *
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }

    /**
     * Sets the Place of the User.
     *
     * @param string $place
     * @return $this
     */
    public function setPlace(string $place): Joins
    {
        $this->place = $place;

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
    public function setSkill(int $skill): Joins
    {
        $this->skill = $skill;

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
    public function setTeamId(int $teamId): Joins
    {
        $this->teamId = $teamId;

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
    public function setLocale(string $locale): Joins
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Sets the created Date of the Join.
     *
     * @param string $dateCreated
     * @return $this
     */
    public function setDateCreated(string $dateCreated): Joins
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Gets the created Date of the Join.
     *
     * @return string
     */
    public function getDateCreated(): string
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
    public function setText(string $text): Joins
    {
        $this->text = $text;

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
    public function setDecision(int $decision): Joins
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
    public function setUndecided(int $undecided): Joins
    {
        $this->undecided = $undecided;

        return $this;
    }

    /**
     * Get Array of this Model
     *
     * @param bool $withId
     * @return array
     * @since 1.22.0
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'userId' => $this->getUserId(),
                'name' => $this->getName(),
                'email' => $this->getEmail(),
                'gender' => $this->getGender(),
                'birthday' => $this->getBirthday(),
                'place' => $this->getPlace(),
                'skill' => $this->getSkill(),
                'teamId' => $this->getTeamId(),
                'locale' => $this->getLocale(),
                'dateCreated' => $this->getDateCreated(),
                'text' => $this->getText(),
                'decision' => $this->getDecision(),
                'undecided' => $this->getUndecided()
            ]
        );
    }
}
