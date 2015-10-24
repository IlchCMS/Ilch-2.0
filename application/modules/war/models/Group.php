<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Models;

class Group extends \Ilch\Model
{
    /**
     * The id.
     *
     * @var integer
     */
    protected $id;

    /**
     * The Group Name.
     *
     * @var string
     */
    protected $groupName;

    /**
     * The Group Tag.
     *
     * @var string
     */
    protected $groupTag;

    /**
     * The Group Image.
     *
     * @var string
     */
    protected $groupImage;

    /**
     * The Group ImageThumb.
     *
     * @var string
     */
    protected $groupImageThumb;

    /**
     * The Group Member.
     *
     * @var string
     */
    protected $groupMember;

    /**
     * Gets the id of the group.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the group.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Gets the group name.
     *
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * Sets the group name.
     *
     * @param string $groupName
     */
    public function setGroupName($groupName)
    {
        $this->groupName = (string)$groupName;
    }

    /**
     * Gets the group tag.
     *
     * @return string
     */
    public function getGroupTag()
    {
        return $this->groupTag;
    }

    /**
     * Sets the group tag.
     *
     * @param string $groupTag
     */
    public function setGroupTag($groupTag)
    {
        $this->groupTag = (string)$groupTag;
    }

    /**
     * Gets the group image.
     *
     * @return string
     */
    public function getGroupImage()
    {
        return $this->groupImage;
    }

    /**
     * Sets the group Image.
     *
     * @param string $groupImage
     */
    public function setGroupImage($groupImage)
    {
        $this->groupImage = (string)$groupImage;
    }

    /**
     * Gets the group ImageThumb.
     *
     * @return string
     */
    public function getGroupImageThumb()
    {
        return $this->groupImageThumb;
    }

    /**
     * Sets the group ImageThumb.
     *
     * @param string $groupImageThumb
     */
    public function setGroupImageThumb($groupImageThumb)
    {
        $this->groupImageThumb = (string)$groupImageThumb;
    }

    /**
     * Gets the group Member.
     *
     * @return string
     */
    public function getGroupMember()
    {
        return $this->groupMember;
    }

    /**
     * Sets the group Member.
     *
     * @param string $groupMember
     */
    public function setGroupMember($groupMember)
    {
        $this->groupMember = (string)$groupMember;
    }
}
