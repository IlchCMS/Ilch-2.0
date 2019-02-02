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
     * The Group Desc.
     *
     * @var string
     */
    protected $groupDesc;

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
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
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
     * @return $this
     */
    public function setGroupName($groupName)
    {
        $this->groupName = (string)$groupName;

        return $this;
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
     * @return $this
     */
    public function setGroupTag($groupTag)
    {
        $this->groupTag = (string)$groupTag;

        return $this;
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
     * @return $this
     */
    public function setGroupImage($groupImage)
    {
        $this->groupImage = (string)$groupImage;

        return $this;
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
     * @return $this
     */
    public function setGroupImageThumb($groupImageThumb)
    {
        $this->groupImageThumb = (string)$groupImageThumb;

        return $this;
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
     * @return $this
     */
    public function setGroupMember($groupMember)
    {
        $this->groupMember = (string)$groupMember;

        return $this;
    }

    /**
     * Gets the group Desc.
     *
     * @return string
     */
    public function getGroupDesc()
    {
        return $this->groupDesc;
    }

    /**
     * Sets the group Desc.
     *
     * @param string $groupDesc
     * @return $this
     */
    public function setGroupDesc($groupDesc)
    {
        $this->groupDesc = (string)$groupDesc;

        return $this;
    }
}
