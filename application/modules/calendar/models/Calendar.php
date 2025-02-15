<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Models;

class Calendar extends \Ilch\Model
{
    /**
     * The id of the calendar.
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * The uid of the calendar.
     * This property defines the persistent, globally unique identifier for the calendar component.
     *
     * @var string
     * @see https://icalendar.org/New-Properties-for-iCalendar-RFC-7986/5-3-uid-property.html
     */
    protected string $uid = '';

    /**
     * The title of the calendar.
     *
     * @var string
     */
    protected string $title = '';

    /**
     * The place of the calendar.
     *
     * @var string
     */
    protected string $place = '';

    /**
     * The start date of the calendar.
     *
     * @var string
     */
    protected string $start = '';

    /**
     * The end date of the calendar.
     *
     * @var string
     */
    protected string $end = '';

    /**
     * The text of the calendar.
     *
     * @var string
     */
    protected string $text = '';

    /**
     * The color of the calendar.
     *
     * @var string
     */
    protected string $color = '';

    /**
     * The period day of the calendar.
     *
     * @var int
     */
    protected int $periodDay = 0;

    /**
     * Read access of the calendar.
     *
     * @var string
     */
    protected string $readAccess = '';

    /**
     * period day of the calendar.
     *
     * @var string
     */
    protected string $periodType = '';

    /**
     * Repeat event until a specific date.
     *
     * @var string
     */
    protected string $repeatUntil = '';

    /**
     * Gets the id of the calendar.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the calendar.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Calendar
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the uid (UUID) of the calendar.
     *
     * @return string
     */
    public function getUid(): string
    {
        return $this->uid;
    }

    /**
     * Set the uid (UUID) of the calendar.
     *
     * @param string $uid
     * @return Calendar
     */
    public function setUid(string $uid): Calendar
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * Gets the title of the calendar.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the calendar.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Calendar
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the place of the calendar.
     *
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }

    /**
     * Sets the place of the calendar.
     *
     * @param string $place
     * @return $this
     */
    public function setPlace(string $place): Calendar
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Gets the start date of the calendar.
     *
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * Sets the start date of the calendar.
     *
     * @param string $start
     * @return $this
     */
    public function setStart(string $start): Calendar
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Gets the end date of the calendar.
     *
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * Sets the end date of the calendar.
     *
     * @param string $end
     * @return $this
     */
    public function setEnd(string $end): Calendar
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Gets the text of the calendar.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the calendar.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Calendar
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the color of the calendar.
     *
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * Sets the color of the calendar.
     *
     * @param string $color
     * @return $this
     */
    public function setColor(string $color): Calendar
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Gets the period day of the calendar.
     *
     * @return int
     */
    public function getPeriodDay(): int
    {
        return $this->periodDay;
    }

    /**
     * Sets the period day of the calendar.
     *
     * @param int $periodDay
     * @return $this
     */
    public function setPeriodDay(int $periodDay): Calendar
    {
        $this->periodDay = $periodDay;

        return $this;
    }

    /**
     * Gets the read access.
     *
     * @return string
     */
    public function getReadAccess(): string
    {
        return $this->readAccess;
    }
    /**
     * Sets the read access.
     *
     * @param string $readAccess
     * @return $this
     */
    public function setReadAccess(string $readAccess): Calendar
    {
        $this->readAccess = $readAccess;
        return $this;
    }

    /**
     * Gets the period type of the calendar.
     *
     * @return string
     */
    public function getPeriodType(): string
    {
        return $this->periodType;
    }

    /**
     * Sets the period type of the calendar.
     *
     * @param string $periodType
     * @return $this
     */
    public function setPeriodType(string $periodType): Calendar
    {
        $this->periodType = $periodType;
        return $this;
    }

    /**
     * Gets the date of until which date the event should be repeated.
     *
     * @return string
     */
    public function getRepeatUntil(): string
    {
        return $this->repeatUntil;
    }

    /**
     * Sets the date of until which date the event should be repeated.
     *
     * @param string $repeatUntil
     * @return Calendar
     */
    public function setRepeatUntil(string $repeatUntil): Calendar
    {
        $this->repeatUntil = $repeatUntil;
        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'uid' => $this->getUid(),
                'title' => $this->getTitle(),
                'place' => $this->getPlace(),
                'start' => $this->getStart(),
                'end' => $this->getEnd(),
                'text' => $this->getText(),
                'color' => $this->getColor(),
                'period_day' => $this->getPeriodDay(),
                'period_type' => $this->getPeriodType(),
                'repeat_until' => $this->getRepeatUntil(),
                'read_access_all' => ($this->getReadAccess() === 'all' ? 1 : 0),
            ]
        );
    }

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Calendar
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['uid'])) {
            $this->setUid($entries['uid']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['place'])) {
            $this->setPlace($entries['place']);
        }
        if (isset($entries['start'])) {
            $this->setStart($entries['start']);
        }
        if (isset($entries['end'])) {
            $this->setEnd($entries['end']);
        }
        if (isset($entries['text'])) {
            $this->setText($entries['text']);
        }
        if (isset($entries['color'])) {
            $this->setColor($entries['color']);
        }
        if (isset($entries['period_day'])) {
            $this->setPeriodDay($entries['period_day']);
        }
        if (isset($entries['read_access'])) {
            $this->setReadAccess($entries['read_access']);
        }
        if (isset($entries['read_access_all'])) {
            if ($entries['read_access_all']) {
                $this->setReadAccess('all');
            }
        }
        if (isset($entries['period_type'])) {
            $this->setPeriodType($entries['period_type']);
        }
        if (isset($entries['repeat_until'])) {
            $this->setRepeatUntil($entries['repeat_until']);
        }

        return $this;
    }
}
