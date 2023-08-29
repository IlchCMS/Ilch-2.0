<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Models;

use Ilch\Model;

class War extends Model
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The War Enemy.
     *
     * @var int
     */
    protected $warEnemy = 0;

    /**
     * The War Enemy Tag.
     *
     * @var string
     */
    protected $warEnemyTag = '';

    /**
     * The War Group.
     *
     * @var int
     */
    protected $warGroup = 0;

    /**
     * The War Group Tag.
     *
     * @var string
     */
    protected $warGroupTag = '';

    /**
     * The War Time.
     *
     * @var string
     */
    protected $warTime = '';

    /**
     * The War Maps.
     *
     * @var string
     */
    protected $warMaps = '';

    /**
     * The War Server.
     *
     * @var string
     */
    protected $warServer = '';

    /**
     * The War Password.
     *
     * @var string
     */
    protected $warPassword = '';

    /**
     * The War Xonx.
     *
     * @var string
     */
    protected $warXonx = '';

    /**
     * The War Game.
     *
     * @var string
     */
    protected $warGame = '';

    /**
     * The War Matchtype.
     *
     * @var string
     */
    protected $warMatchtype = '';

    /**
     * The War Report.
     *
     * @var string
     */
    protected $warReport = '';

    /**
     * The War Status.
     *
     * @var int
     */
    protected $warStatus = '';

    /**
     * The show value (hide or show in calendar) of the training.
     *
     * @var int
     */
    protected $show = 0;
    /**
     * The readaccess of the training.
     *
     * @var string
     */
    protected $readAccess = '';

    /**
     * The last Accept Time.
     *
     * @var int
     */
    protected $lastAcceptTime = 0;

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): War
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['enemy'])) {
            $this->setWarEnemy($entries['enemy']);
        }
        if (isset($entries['group'])) {
            $this->setWarGroup($entries['group']);
        }
        if (isset($entries['time'])) {
            $this->setWarTime($entries['time']);
        }
        if (isset($entries['maps'])) {
            $this->setWarMaps($entries['maps']);
        }
        if (isset($entries['server'])) {
            $this->setWarServer($entries['server']);
        }
        if (isset($entries['password'])) {
            $this->setWarPassword($entries['password']);
        }
        if (isset($entries['xonx'])) {
            $this->setWarXonx($entries['xonx']);
        }
        if (isset($entries['game'])) {
            $this->setWarGame($entries['game']);
        }
        if (isset($entries['matchtype'])) {
            $this->setWarMatchtype($entries['matchtype']);
        }
        if (isset($entries['report'])) {
            $this->setWarReport($entries['report']);
        }
        if (isset($entries['status'])) {
            $this->setWarStatus($entries['status']);
        }
        if (isset($entries['show'])) {
            $this->setShow($entries['show']);
        }
        if (isset($entries['read_access'])) {
            $this->setReadAccess($entries['read_access']);
        }
        if (isset($entries['lastaccepttime'])) {
            $this->setLastAcceptTime($entries['lastaccepttime']);
        }
        if (isset($entries['read_access_all'])) {
            if ($entries['read_access_all']) {
                $this->setReadAccess('all');
            }
        }

        if (isset($entries['war_groups'])) {
            $this->setWarGroupTag($entries['war_groups']);
        }
        if (isset($entries['war_enemy'])) {
            $this->setWarEnemyTag($entries['war_enemy']);
        }

        return $this;
    }

    /**
     * Gets the id of the group.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the group.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): War
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the war enemy.
     *
     * @return int
     */
    public function getWarEnemy(): int
    {
        return $this->warEnemy;
    }

    /**
     * Sets the war enemy.
     *
     * @param int $warEnemy
     * @return $this
     */
    public function setWarEnemy(int $warEnemy): War
    {
        $this->warEnemy = $warEnemy;

        return $this;
    }

    /**
     * Gets the war enemy tag.
     *
     * @return string
     */
    public function getWarEnemyTag(): string
    {
        return $this->warEnemyTag;
    }

    /**
     * Sets the war enemy tag.
     *
     * @param string $warEnemyTag
     * @return $this
     */
    public function setWarEnemyTag(string $warEnemyTag): War
    {
        $this->warEnemyTag = $warEnemyTag;

        return $this;
    }

    /**
     * Gets the war group.
     *
     * @return int
     */
    public function getWarGroup(): int
    {
        return $this->warGroup;
    }

    /**
     * Sets the war group.
     *
     * @param int $warGroup
     * @return $this
     */
    public function setWarGroup(int $warGroup): War
    {
        $this->warGroup = $warGroup;

        return $this;
    }

    /**
     * Gets the war group tag.
     *
     * @return string
     */
    public function getWarGroupTag(): string
    {
        return $this->warGroupTag;
    }

    /**
     * Sets the war group tag.
     *
     * @param string $warGroupTag
     * @return $this
     */
    public function setWarGroupTag(string $warGroupTag): War
    {
        $this->warGroupTag = $warGroupTag;

        return $this;
    }

    /**
     * Gets the war time.
     *
     * @return string
     */
    public function getWarTime(): string
    {
        return $this->warTime;
    }

    /**
     * Sets the war time.
     *
     * @param string $warTime
     * @return $this
     */
    public function setWarTime(string $warTime): War
    {
        $this->warTime = $warTime;

        return $this;
    }

    /**
     * Gets the war maps.
     *
     * @return string
     */
    public function getWarMaps(): string
    {
        return $this->warMaps;
    }

    /**
     * Sets the war maps.
     *
     * @param string $warMaps
     * @return $this
     */
    public function setWarMaps(string $warMaps): War
    {
        $this->warMaps = $warMaps;

        return $this;
    }

    /**
     * Gets the war server.
     *
     * @return string
     */
    public function getWarServer(): string
    {
        return $this->warServer;
    }

    /**
     * Sets the war server.
     *
     * @param string $warServer
     * @return $this
     */
    public function setWarServer(string $warServer): War
    {
        $this->warServer = $warServer;

        return $this;
    }

    /**
     * Gets the war password.
     *
     * @return string
     */
    public function getWarPassword(): string
    {
        return $this->warPassword;
    }

    /**
     * Sets the war password.
     *
     * @param string $warPassword
     * @return $this
     */
    public function setWarPassword(string $warPassword): War
    {
        $this->warPassword = $warPassword;

        return $this;
    }

    /**
     * Gets the war xonx.
     *
     * @return string
     */
    public function getWarXonx(): string
    {
        return $this->warXonx;
    }

    /**
     * Sets the war Xonx.
     *
     * @param string $warXonx
     * @return $this
     */
    public function setWarXonx(string $warXonx): War
    {
        $this->warXonx = $warXonx;

        return $this;
    }

    /**
     * Gets the war game.
     *
     * @return string
     */
    public function getWarGame(): string
    {
        return $this->warGame;
    }

    /**
     * Sets the war game.
     *
     * @param string $warGame
     * @return $this
     */
    public function setWarGame(string $warGame): War
    {
        $this->warGame = $warGame;

        return $this;
    }

    /**
     * Gets the war matchtype.
     *
     * @return string
     */
    public function getWarMatchtype(): string
    {
        return $this->warMatchtype;
    }

    /**
     * Sets the war matchtype.
     *
     * @param string $warMatchtype
     * @return $this
     */
    public function setWarMatchtype(string $warMatchtype): War
    {
        $this->warMatchtype = $warMatchtype;

        return $this;
    }

    /**
     * Gets the war report.
     *
     * @return string
     */
    public function getWarReport(): string
    {
        return $this->warReport;
    }

    /**
     * Sets the war report.
     *
     * @param string $warReport
     * @return $this
     */
    public function setWarReport(string $warReport): War
    {
        $this->warReport = $warReport;

        return $this;
    }

    /**
     * Gets the war status.
     *
     * @return int
     */
    public function getWarStatus(): int
    {
        return $this->warStatus;
    }

    /**
     * Sets the war status.
     *
     * @param int $warStatus
     * @return $this
     */
    public function setWarStatus(int $warStatus): War
    {
        $this->warStatus = $warStatus;

        return $this;
    }

    /**
     * Gets show of the war.
     *
     * @return int
     */
    public function getShow(): int
    {
        return $this->show;
    }

    /**
     * Sets show of the war.
     *
     * @param int $show
     * @return $this
     */
    public function setShow(int $show): War
    {
        $this->show = $show;

        return $this;
    }
    /**
     * Gets the read access of the war.
     *
     * @return string
     */
    public function getReadAccess(): string
    {
        return $this->readAccess;
    }
    /**
     * Sets the read access of the war.
     *
     * @param string $readAccess
     * @return $this
     */
    public function setReadAccess(string $readAccess): War
    {
        $this->readAccess = $readAccess;

        return $this;
    }

    /**
     * Gets the last Accept Time.
     *
     * @return int
     */
    public function getLastAcceptTime(): int
    {
        return $this->lastAcceptTime;
    }

    /**
     * Sets the last Accept Time.
     *
     * @param int $lastAcceptTime
     * @return $this
     */
    public function setLastAcceptTime(int $lastAcceptTime): War
    {
        $this->lastAcceptTime = $lastAcceptTime;

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
                'enemy'             => $this->getWarEnemy(),
                'group'             => $this->getWarGroup(),
                'time'              => $this->getWarTime(),
                'maps'              => $this->getWarMaps(),
                'server'            => $this->getWarServer(),
                'password'          => $this->getWarPassword(),
                'xonx'              => $this->getWarXonx(),
                'game'              => $this->getWarGame(),
                'matchtype'         => $this->getWarMatchtype(),
                'report'            => $this->getWarReport(),
                'status'            => $this->getWarStatus(),
                'show'              => $this->getShow(),
                'lastaccepttime'    => $this->getLastAcceptTime(),
                'read_access_all'    => ($this->getReadAccess() === 'all' ? 1 : 0)
            ]
        );
    }
}
