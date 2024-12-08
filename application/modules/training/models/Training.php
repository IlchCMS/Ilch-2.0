<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Training\Models;

class Training extends \Ilch\Model
{
    /**
     * The id of the training.
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * The title of the training.
     *
     * @var string
     */
    protected string $title = '';

    /**
     * The date of the training.
     *
     * @var string
     */
    protected string $date = '';

    /**
     * The end of the training.
     *
     * @var string
     */
    protected string $end = '';

    /**
     * The period day of the training.
     *
     * @var int
     */
    protected int $periodDay = 0;

    /**
     * period day of the training.
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
     * The place of the training.
     *
     * @var string
     */
    protected string $place = '';

    /**
     * The contact of the training.
     *
     * @var int
     */
    protected int $contact = 0;

    /**
     * The voice server of the training.
     *
     * @var bool
     */
    protected bool $voiceServer = false;

    /**
     * The voice server ip of the training.
     *
     * @var string
     */
    protected string $voiceServerIP = '';

    /**
     * The voice server pw of the training.
     *
     * @var string
     */
    protected string $voiceServerPW = '';

    /**
     * The game server of the training.
     *
     * @var bool
     */
    protected bool $gameServer = false;

    /**
     * The game server ip of the training.
     *
     * @var string
     */
    protected string $gameServerIP = '';

    /**
     * The game server pw of the training.
     *
     * @var string
     */
    protected string $gameServerPW = '';

    /**
     * The text of the training.
     *
     * @var string
     */
    protected string $text = '';

    /**
     * The show value (hide or show in calendar) of the training.
     *
     * @var bool
     */
    protected bool $show = false;

    /**
     * The readaccess of the training.
     *
     * @var string
     */
    protected string $readAccess = '2,3';

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Training
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['date'])) {
            $this->setDate($entries['date']);
        }
        if (isset($entries['end'])) {
            $this->setEnd($entries['end']);
        }
        if (isset($entries['period_type'])) {
            $this->setPeriodType($entries['period_type']);
        }
        if (isset($entries['period_day'])) {
            $this->setPeriodDay($entries['period_day']);
        }
        if (isset($entries['repeat_until'])) {
            $this->setRepeatUntil($entries['repeat_until']);
        }
        if (isset($entries['place'])) {
            $this->setPlace($entries['place']);
        }
        if (isset($entries['contact'])) {
            $this->setContact($entries['contact']);
        }
        if (isset($entries['voice_server'])) {
            $this->setVoiceServer($entries['voice_server']);
        }
        if (isset($entries['voice_server_ip'])) {
            $this->setVoiceServerIP($entries['voice_server_ip']);
        }
        if (isset($entries['voice_server_pw'])) {
            $this->setVoiceServerPW($entries['voice_server_pw']);
        }
        if (isset($entries['game_server'])) {
            $this->setGameServer($entries['game_server']);
        }
        if (isset($entries['game_server_ip'])) {
            $this->setGameServerIP($entries['game_server_ip']);
        }
        if (isset($entries['game_server_pw'])) {
            $this->setGameServerPW($entries['game_server_pw']);
        }
        if (isset($entries['text'])) {
            $this->setText($entries['text']);
        }
        if (isset($entries['show'])) {
            $this->setShow($entries['show']);
        }
        if (isset($entries['read_access'])) {
            $this->setReadAccess($entries['read_access']);
        }
        if (isset($entries['access_all'])) {
            if ($entries['access_all']) {
                $this->setReadAccess('all');
            }
        }
        return $this;
    }

    /**
     * Gets the id of the training.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the training.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Training
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the title of the training.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the training.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Training
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the date of the training.
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Sets the date of the training.
     *
     * @param string $date
     * @return $this
     */
    public function setDate(string $date): Training
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the end of the training.
     *
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * Set the end of the training.
     *
     * @param string $end
     * @return $this
     */
    public function setEnd(string $end): Training
    {
        $this->end = $end;
        return $this;
    }

    /**
     * Gets the period day of the training.
     *
     * @return int
     */
    public function getPeriodDay(): int
    {
        return $this->periodDay;
    }

    /**
     * Sets the period day of the training.
     *
     * @param int $periodDay
     * @return $this
     */
    public function setPeriodDay(int $periodDay): Training
    {
        $this->periodDay = $periodDay;
        return $this;
    }

    /**
     * Gets the period type of the training.
     *
     * @return string
     */
    public function getPeriodType(): string
    {
        return $this->periodType;
    }

    /**
     * Sets the period type of the training.
     *
     * @param string $periodType
     * @return $this
     */
    public function setPeriodType(string $periodType): Training
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
     * @return $this
     */
    public function setRepeatUntil(string $repeatUntil): Training
    {
        $this->repeatUntil = $repeatUntil;
        return $this;
    }

    /**
     * Gets the place of the training.
     *
     * @return string
     */
    public function getPlace(): string
    {
        return $this->place;
    }

    /**
     * Sets the place of the training.
     *
     * @param string $place
     * @return $this
     */
    public function setPlace(string $place): Training
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Gets the contact of the training.
     *
     * @return int
     */
    public function getContact(): int
    {
        return $this->contact;
    }

    /**
     * Sets the contact of the training.
     *
     * @param int $contact
     * @return $this
     */
    public function setContact(int $contact): Training
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Gets the voice server of the training.
     *
     * @return bool
     */
    public function getVoiceServer(): bool
    {
        return $this->voiceServer;
    }

    /**
     * Sets the voice server of the training.
     *
     * @param bool $voiceServer
     * @return $this
     */
    public function setVoiceServer(bool $voiceServer): Training
    {
        $this->voiceServer = $voiceServer;

        return $this;
    }

    /**
     * Gets the voice server ip of the training.
     *
     * @return string
     */
    public function getVoiceServerIP(): string
    {
        return $this->voiceServerIP;
    }

    /**
     * Sets the voice server ip of the training.
     *
     * @param string $voiceServerIP
     * @return $this
     */
    public function setVoiceServerIP(string $voiceServerIP): Training
    {
        $this->voiceServerIP = $voiceServerIP;

        return $this;
    }

    /**
     * Gets the voice server pw of the training.
     *
     * @return string
     */
    public function getVoiceServerPW(): string
    {
        return $this->voiceServerPW;
    }

    /**
     * Sets the voice server pw of the training.
     *
     * @param string $voiceServerPW
     * @return $this
     */
    public function setVoiceServerPW(string $voiceServerPW): Training
    {
        $this->voiceServerPW = $voiceServerPW;

        return $this;
    }

    /**
     * Gets the game server of the training.
     *
     * @return bool
     */
    public function getGameServer(): bool
    {
        return $this->gameServer;
    }

    /**
     * Sets the game server of the training.
     *
     * @param bool $gameServer
     * @return $this
     */
    public function setGameServer(bool $gameServer): Training
    {
        $this->gameServer = $gameServer;

        return $this;
    }

    /**
     * Gets the game server ip of the training.
     *
     * @return string
     */
    public function getGameServerIP(): string
    {
        return $this->gameServerIP;
    }

    /**
     * Sets the game server ip of the training.
     *
     * @param string $gameServerIP
     * @return $this
     */
    public function setGameServerIP(string $gameServerIP): Training
    {
        $this->gameServerIP = $gameServerIP;

        return $this;
    }

    /**
     * Gets the game server pw of the training.
     *
     * @return string
     */
    public function getGameServerPW(): string
    {
        return $this->gameServerPW;
    }

    /**
     * Sets the game server pw of the training.
     *
     * @param string $gameServerPW
     * @return $this
     */
    public function setGameServerPW(string $gameServerPW): Training
    {
        $this->gameServerPW = $gameServerPW;

        return $this;
    }

    /**
     * Gets the text of the training.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the training.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Training
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets show of the training.
     *
     * @return bool
     */
    public function getShow(): bool
    {
        return $this->show;
    }

    /**
     * Sets show of the training.
     *
     * @param bool $show
     * @return $this
     */
    public function setShow(bool $show): Training
    {
        $this->show = $show;

        return $this;
    }

    /**
     * Gets the read access of the training.
     *
     * @return string
     */
    public function getReadAccess(): string
    {
        return $this->readAccess;
    }

    /**
     * Sets the read access of the training.
     *
     * @param string $readAccess
     *
     * @return $this
     */
    public function setReadAccess(string $readAccess): Training
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
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'title' => $this->getTitle(),
                'date' => $this->getDate(),
                'end' => $this->getEnd(),
                'period_day' => $this->getPeriodDay(),
                'period_type' => $this->getPeriodType(),
                'repeat_until' => $this->getRepeatUntil(),
                'place' => $this->getPlace(),
                'contact' => $this->getContact(),
                'voice_server' => $this->getVoiceServer(),
                'voice_server_ip' => $this->getVoiceServerIP(),
                'voice_server_pw' => $this->getVoiceServerPW(),
                'game_server' => $this->getGameServer(),
                'game_server_ip' => $this->getGameServerIP(),
                'game_server_pw' => $this->getGameServerPW(),
                'text' => $this->getText(),
                'show' => $this->getShow(),
                'access_all' => ($this->getReadAccess() === 'all' ? 1 : 0)
            ]
        );
    }
}
