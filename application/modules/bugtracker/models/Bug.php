<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Models;

class Bug extends \Ilch\Model
{
    /**
     * ID of the Bug
     * @var int
     */
    private $id;

    /**
     * Sub Category of the Bug
     * @var SubCategory
     */
    private $subCategory;

    /**
     * @var int
     */
    private $priority;
    private $title;
    private $description;    
    private $user;
    private $assignedUsers;

    /**
     * Progress of the Bug in Percent
     * @var int
     */
    private $progress;
    private $status;
    private $likes;
    private $dislikes;

    /**
     * Determine if Bug is Intern or not
     * @var bool
     */
    private $internOnly;
    private $updateTime;
    private $createTime;

    /**
     * Comments of the Bug
     * @var Comment[]
     */
    private $comments;
  

    function __construct($id, $subCategory, $priority, $title, $description, $user, $assignedUsers, $progress, $status, $likes, $dislikes, $internOnly, $updateTime, $createTime, $comments)
    {
        $this->id = $id;
        $this->subCategory = $subCategory;
        $this->priority = $priority;
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
        $this->assignedUsers = $assignedUsers;
        $this->progress = $progress;
        $this->status = $status;
        $this->likes = $likes;
        $this->dislikes = $dislikes;
        $this->updateTime = $updateTime;
        $this->internOnly = $internOnly;
        $this->createTime = $createTime;

        $this->comments = $comments;  
    }

    public function getID()
    {
        return $this->id;
    }

    public function getSubCategory()
    {
        return $this->subCategory;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAssignedUsers()
    {
        return $this->assignedUsers;
    }

    public function getProgress()
    {
        return $this->progress;
    }

    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array[]
     */
    public function getLikes()
    {
        return $this->likes;
    }

    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return array[]
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }

    public function setDislikes($dislikes)
    {
        $this->dislikes = $dislikes;
    }

    public function isInternOnly()
    {
        return (boolean) $this->internOnly;
    }

    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    public function getCreateTime()
    {
        return $this->createTime;
    }

    public function getComments()
    {
        return $this->comments;
    }  
}
