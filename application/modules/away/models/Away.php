<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Away\Models;

class Away extends \Ilch\Model
{
    /**
     * The id of the away.
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * The userId of the away.
     *
     * @var int
     */
    protected int $userId = 0;

    /**
     * The reason of the away.
     *
     * @var string
     */
    protected string $reason = '';

    /**
     * The start of the away.
     *
     * @var string
     */
    protected string $start = '';

    /**
     * The end of the away.
     *
     * @var string
     */
    protected string $end = '';

    /**
     * The text of the away.
     *
     * @var string
     */
    protected string $text = '';

    /**
     * The status of the away.
     *
     * @var int
     */
    protected int $status = 2;

    /**
     * The show of the away.
     *
     * @var int
     */
    protected int $show = 0;

    /**
     * @param array $entries
     * @return $this
     * @since 1.8.1
     */
    public function setByArray(array $entries): Away
    {
        if (!empty($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (!empty($entries['user_id'])) {
            $this->setUserId($entries['user_id']);
        }
        if (!empty($entries['reason'])) {
            $this->setReason($entries['reason']);
        }
        if (!empty($entries['start'])) {
            $this->setStart($entries['start']);
        }
        if (!empty($entries['end'])) {
            $this->setEnd($entries['end']);
        }
        if (!empty($entries['text'])) {
            $this->setText($entries['text']);
        }
        if (!empty($entries['status'])) {
            $this->setStatus($entries['status']);
        }
        if (!empty($entries['show'])) {
            $this->setShow($entries['show']);
        }

        return $this;
    }

    /**
     * Gets the id of the away.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the away.
     *
     * @param int $id
     * @return Away
     */
    public function setId(int $id): Away
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the userId of the away.
     *
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Sets the userId of the away.
     *
     * @param int $userId
     * @return Away
     */
    public function setUserId(int $userId): Away
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Gets the reason of the away.
     *
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * Sets the reason of the away.
     *
     * @param string $reason
     * @return Away
     */
    public function setReason(string $reason): Away
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Gets the start of the away.
     *
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * Sets the start of the away.
     *
     * @param string $start
     * @return Away
     */
    public function setStart(string $start): Away
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Gets the end of the away.
     *
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * Sets the end of the away.
     *
     * @param string $end
     * @return Away
     */
    public function setEnd(string $end): Away
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Gets the text of the away.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the away.
     *
     * @param string $text
     * @return Away
     */
    public function setText(string $text): Away
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the status of the away.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Sets the status of the away.
     *
     * @param int $status 0: declined, 1: approved, 2: reported
     * @return Away
     */
    public function setStatus(int $status): Away
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Gets the show of the away.
     *
     * @return int
     */
    public function getShow(): int
    {
        return $this->show;
    }

    /**
     * Sets the show of the away.
     *
     * @param int $show
     * @return Away
     */
    public function setShow(int $show): Away
    {
        $this->show = $show;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.8.1
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'user_id' =>    $this->getUserId(),
                'reason' =>     $this->getReason(),
                'start' =>      $this->getStart(),
                'end' =>        $this->getEnd(),
                'text' =>       $this->getText(),
                'show' =>       $this->getShow(),
                'status' =>     $this->getStatus()
            ]
        );
    }
}
