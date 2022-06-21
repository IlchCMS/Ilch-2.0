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
    protected $id = 0;

    /**
     * The title of the calendar.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The place of the calendar.
     *
     * @var string
     */
    protected $place = '';

    /**
     * The start date of the calendar.
     *
     * @var string
     */
    protected $start = '';

    /**
     * The end date of the calendar.
     *
     * @var string
     */
    protected $end = '';

    /**
     * The text of the calendar.
     *
     * @var string
     */
    protected $text = '';

    /**
     * The color of the calendar.
     *
     * @var string
     */
    protected $color = '';

    /**
     * The period day of the calendar.
     *
     * @var int
     */
    protected $periodDay = 0;

    /**
     * Read access of the article.
     *
     * @var string
     */
    protected $readAccess = '';

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray($entries): Calendar
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
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

        return $this;
    }

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
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     */
    public function getArray(bool $withId = true)
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'title' => $this->getTitle(),
                'place' => $this->getPlace(),
                'start' => $this->getStart(),
                'end' => $this->getEnd(),
                'text' => $this->getText(),
                'color' => $this->getColor(),
                'period_day' => $this->getPeriodDay(),
                'read_access_all'    => ($this->getReadAccess() === 'all' ? 1 : 0)
            ]
        );
    }
}
