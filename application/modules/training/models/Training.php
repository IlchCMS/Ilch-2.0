<?php
/**
 * @copyright Ilch 2.0
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
    protected $id;

    /**
     * The title of the training.
     *
     * @var string
     */
    protected $title;

    /**
     * The date of the training.
     *
     * @var string
     */
    protected $date;

    /**
     * The time of the training.
     *
     * @var string
     */
    protected $time;

    /**
     * The place of the training.
     *
     * @var string
     */
    protected $place;

    /**
     * The contact of the training.
     *
     * @var int
     */
    protected $contact;

    /**
     * The voice server of the training.
     *
     * @var int
     */
    protected $voiceServer;

    /**
     * The voice server ip of the training.
     *
     * @var string
     */
    protected $voiceServerIP;

    /**
     * The voice server pw of the training.
     *
     * @var string
     */
    protected $voiceServerPW;

    /**
     * The game server of the training.
     *
     * @var int
     */
    protected $gameServer;

    /**
     * The game server ip of the training.
     *
     * @var string
     */
    protected $gameServerIP;

    /**
     * The game server pw of the training.
     *
     * @var string
     */
    protected $gameServerPW;

    /**
     * The text of the training.
     *
     * @var string
     */
    protected $text;

    /**
     * Gets the id of the training.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the training.
     *
     * @param int $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the title of the training.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title of the training.
     *
     * @param string $title
     * @return this
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;

        return $this;
    }

    /**
     * Gets the date of the training.
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the date of the training.
     *
     * @param string $date
     * @return this
     */
    public function setDate($date)
    {
        $this->date = (string)$date;

        return $this;
    }

    /**
     * Gets the time of the training.
     *
     * @return int
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Sets the time of the training.
     *
     * @param int $time
     * @return this
     */
    public function setTime($time)
    {
        $this->time = (int)$time;

        return $this;
    }

    /**
     * Gets the place of the training.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Sets the place of the training.
     *
     * @param string $place
     * @return this
     */
    public function setPlace($place)
    {
        $this->place = (string)$place;

        return $this;
    }

    /**
     * Gets the contact of the training.
     *
     * @return int
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Sets the contact of the training.
     *
     * @param string $contact
     * @return this
     */
    public function setContact($contact)
    {
        $this->contact = (int)$contact;

        return $this;
    }

    /**
     * Gets the voice server of the training.
     *
     * @return int
     */
    public function getVoiceServer()
    {
        return $this->voiceServer;
    }

    /**
     * Sets the voice server of the training.
     *
     * @param int $voiceServer
     * @return this
     */
    public function setVoiceServer($voiceServer)
    {
        $this->voiceServer = (int)$voiceServer;

        return $this;
    }

    /**
     * Gets the voice server ip of the training.
     *
     * @return string
     */
    public function getVoiceServerIP()
    {
        return $this->voiceServerIP;
    }

    /**
     * Sets the voice server ip of the training.
     *
     * @param string $voiceServerIP
     * @return this
     */
    public function setVoiceServerIP($voiceServerIP)
    {
        $this->voiceServerIP = (string)$voiceServerIP;

        return $this;
    }

    /**
     * Gets the voice server pw of the training.
     *
     * @return string
     */
    public function getVoiceServerPW()
    {
        return $this->voiceServerPW;
    }

    /**
     * Sets the voice server pw of the training.
     *
     * @param string $voiceServerPW
     * @return this
     */
    public function setVoiceServerPW($voiceServerPW)
    {
        $this->voiceServerPW = (string)$voiceServerPW;

        return $this;
    }

    /**
     * Gets the game server of the training.
     *
     * @return int
     */
    public function getGameServer()
    {
        return $this->gameServer;
    }

    /**
     * Sets the game server of the training.
     *
     * @param int $gameServer
     * @return this
     */
    public function setGameServer($gameServer)
    {
        $this->gameServer = (int)$gameServer;

        return $this;
    }

    /**
     * Gets the game server ip of the training.
     *
     * @return string
     */
    public function getGameServerIP()
    {
        return $this->gameServerIP;
    }

    /**
     * Sets the game server ip of the training.
     *
     * @param string $gameServerIP
     * @return this
     */
    public function setGameServerIP($gameServerIP)
    {
        $this->gameServerIP = (string)$gameServerIP;

        return $this;
    }

    /**
     * Gets the game server pw of the training.
     *
     * @return string
     */
    public function getGameServerPW()
    {
        return $this->gameServerPW;
    }

    /**
     * Sets the game server pw of the training.
     *
     * @param string $gameServerPW
     * @return this
     */
    public function setGameServerPW($gameServerPW)
    {
        $this->gameServerPW = (string)$gameServerPW;

        return $this;
    }

    /**
     * Gets the text of the training.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets the text of the training.
     *
     * @param string $text
     * @return this
     */
    public function setText($text)
    {
        $this->text = (string)$text;

        return $this;
    }
}
