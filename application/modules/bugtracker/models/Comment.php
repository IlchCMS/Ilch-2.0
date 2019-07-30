<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Models;

class Comment extends \Ilch\Model
{
    private $id;
    private $bugID;
    private $content;
    private $user;
    private $internOnly;
    private $createTime;

    function __construct($id, $bugID, $content, $user, $internOnly, $createTime)
    {
        $this->id = (int) $id;
        $this->bugID = $bugID;
        $this->content = $content;
        $this->user = $user;
        $this->internOnly = $internOnly;
        $this->createTime = $createTime;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getBugID()
    {
        return $this->bugID;
    }

    public function setBugID($bugID)
    {
        $this->bugID = $bugID;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function isInternOnly()
    {
        return $this->internOnly;
    }

    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;
    }

    public function getCreateTime()
    {
        return $this->createTime;
    }
}
