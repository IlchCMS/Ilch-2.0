<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Models;

class War extends \Ilch\Model
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id;

    /**
     * The War Enemy.
     *
     * @var int
     */
    protected $warEnemy;

    /**
     * The War Enemy Tag.
     *
     * @var int
     */
    protected $warEnemyTag;

    /**
     * The War Group.
     *
     * @var int
     */
    protected $warGroup;

    /**
     * The War Group Tag.
     *
     * @var int
     */
    protected $warGroupTag;

    /**
     * The War Time.
     *
     * @var string
     */
    protected $warTime;

    /**
     * The War Maps.
     *
     * @var string
     */
    protected $warMaps;

    /**
     * The War Server.
     *
     * @var string
     */
    protected $warServer;

    /**
     * The War Password.
     *
     * @var string
     */
    protected $warPassword;

    /**
     * The War Xonx.
     *
     * @var string
     */
    protected $warXonx;

    /**
     * The War Game.
     *
     * @var string
     */
    protected $warGame;

    /**
     * The War Matchtype.
     *
     * @var string
     */
    protected $warMatchtype;

    /**
     * The War Report.
     *
     * @var string
     */
    protected $warReport;

    /**
     * The War Status.
     *
     * @var int
     */
    protected $warStatus;

    /**
     * Gets the id of the group.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the group.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Gets the war enemy.
     *
     * @return int
     */
    public function getWarEnemy()
    {
        return $this->warEnemy;
    }

    /**
     * Sets the war enemy.
     *
     * @param int $warEnemy
     */
    public function setWarEnemy($warEnemy)
    {
        $this->warEnemy = (string)$warEnemy;
    }

    /**
     * Gets the war enemy tag.
     *
     * @return int
     */
    public function getWarEnemyTag()
    {
        return $this->warEnemyTag;
    }

    /**
     * Sets the war enemy tag.
     *
     * @param int $warEnemyTag
     */
    public function setWarEnemyTag($warEnemyTag)
    {
        $this->warEnemyTag = (string)$warEnemyTag;
    }

    /**
     * Gets the war group.
     *
     * @return int
     */
    public function getWarGroup()
    {
        return $this->warGroup;
    }

    /**
     * Sets the war group.
     *
     * @param int $warGroup
     */
    public function setWarGroup($warGroup)
    {
        $this->warGroup = (string)$warGroup;
    }

    /**
     * Gets the war group tag.
     *
     * @return int
     */
    public function getWarGroupTag()
    {
        return $this->warGroupTag;
    }

    /**
     * Sets the war group tag.
     *
     * @param int $warGroupTag
     */
    public function setWarGroupTag($warGroupTag)
    {
        $this->warGroupTag = (string)$warGroupTag;
    }

    /**
     * Gets the war time.
     *
     * @return string
     */
    public function getWarTime()
    {
        return $this->warTime;
    }

    /**
     * Sets the war time.
     *
     * @param string $warTime
     */
    public function setWarTime($warTime)
    {
        $this->warTime = (string)$warTime;
    }

    /**
     * Gets the war maps.
     *
     * @return string
     */
    public function getWarMaps()
    {
        return $this->warMaps;
    }

    /**
     * Sets the war maps.
     *
     * @param string $warMaps
     */
    public function setWarMaps($warMaps)
    {
        $this->warMaps = (string)$warMaps;
    }

    /**
     * Gets the war server.
     *
     * @return string
     */
    public function getWarServer()
    {
        return $this->warServer;
    }

    /**
     * Sets the war server.
     *
     * @param string $warServer
     */
    public function setWarServer($warServer)
    {
        $this->warServer = (string)$warServer;
    }

    /**
     * Gets the war password.
     *
     * @return string
     */
    public function getWarPassword()
    {
        return $this->warPassword;
    }

    /**
     * Sets the war password.
     *
     * @param string $warPassword
     */
    public function setWarPassword($warPassword)
    {
        $this->warPassword = (string)$warPassword;
    }

    /**
     * Gets the war xonx.
     *
     * @return string
     */
    public function getWarXonx()
    {
        return $this->warXonx;
    }

    /**
     * Sets the war Xonx.
     *
     * @param string $warXonx
     */
    public function setWarXonx($warXonx)
    {
        $this->warXonx = (string)$warXonx;
    }

    /**
     * Gets the war game.
     *
     * @return string
     */
    public function getWarGame()
    {
        return $this->warGame;
    }

    /**
     * Sets the war game.
     *
     * @param string $warGame
     */
    public function setWarGame($warGame)
    {
        $this->warGame = (string)$warGame;
    }

    /**
     * Gets the war matchtype.
     *
     * @return string
     */
    public function getWarMatchtype()
    {
        return $this->warMatchtype;
    }

    /**
     * Sets the war matchtype.
     *
     * @param string $warMatchtype
     */
    public function setWarMatchtype($warMatchtype)
    {
        $this->warMatchtype = (string)$warMatchtype;
    }

    /**
     * Gets the war report.
     *
     * @return string
     */
    public function getWarReport()
    {
        return $this->warReport;
    }

    /**
     * Sets the war report.
     *
     * @param string $warReport
     */
    public function setWarReport($warReport)
    {
        $this->warReport = (string)$warReport;
    }

    /**
     * Gets the war status.
     *
     * @return int
     */
    public function getWarStatus()
    {
        return $this->warStatus;
    }

    /**
     * Sets the war status.
     *
     * @param int $warStatus
     */
    public function setWarStatus($warStatus)
    {
        $this->warStatus = (int)$warStatus;
    }
}
