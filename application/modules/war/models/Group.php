<?php
/**
 * @copyright Ilch 2
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
    protected $id = 0;

    /**
     * The Group Name.
     *
     * @var string
     */
    protected $groupName = '';

    /**
     * The Group Tag.
     *
     * @var string
     */
    protected $groupTag = '';

    /**
     * The Group Image.
     *
     * @var string
     */
    protected $groupImage = '';

    /**
     * The Group ImageThumb.
     *
     * @var string
     */
    protected $groupImageThumb = '';

    /**
     * The Group Member.
     *
     * @var string
     */
    protected $groupMember = '';

    /**
     * The Group Desc.
     *
     * @var string
     */
    protected $groupDesc = '';

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Group
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['name'])) {
            $this->setGroupName($entries['name']);
        }
        if (isset($entries['tag'])) {
            $this->setGroupTag($entries['tag']);
        }
        if (isset($entries['image'])) {
            $this->setGroupImage($entries['image']);
        }
        if (isset($entries['member'])) {
            $this->setGroupMember($entries['member']);
        }
        if (isset($entries['desc'])) {
            $this->setGroupDesc($entries['desc']);
        }

        return $this;
    }

    /**
     * Gets the id of the group.
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the group.
     *
     * @param integer $id
     * @return $this
     */
    public function setId(int $id): Group
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the group name.
     *
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->groupName;
    }

    /**
     * Sets the group name.
     *
     * @param string $groupName
     * @return $this
     */
    public function setGroupName(string $groupName): Group
    {
        $this->groupName = $groupName;

        return $this;
    }

    /**
     * Gets the group tag.
     *
     * @return string
     */
    public function getGroupTag(): string
    {
        return $this->groupTag;
    }

    /**
     * Sets the group tag.
     *
     * @param string $groupTag
     * @return $this
     */
    public function setGroupTag(string $groupTag): Group
    {
        $this->groupTag = $groupTag;

        return $this;
    }

    /**
     * Gets the group image.
     *
     * @return string
     */
    public function getGroupImage(): string
    {
        return $this->groupImage;
    }

    /**
     * Sets the group Image.
     *
     * @param string $groupImage
     * @return $this
     */
    public function setGroupImage(string $groupImage): Group
    {
        $this->groupImage = $groupImage;

        return $this;
    }

    /**
     * Gets the group ImageThumb.
     *
     * @return string
     */
    public function getGroupImageThumb(): string
    {
        return $this->groupImageThumb;
    }

    /**
     * Sets the group ImageThumb.
     *
     * @param string $groupImageThumb
     * @return $this
     */
    public function setGroupImageThumb(string $groupImageThumb): Group
    {
        $this->groupImageThumb = $groupImageThumb;

        return $this;
    }

    /**
     * Gets the group Member.
     *
     * @return string
     */
    public function getGroupMember(): string
    {
        return $this->groupMember;
    }

    /**
     * Sets the group Member.
     *
     * @param string $groupMember
     * @return $this
     */
    public function setGroupMember(string $groupMember): Group
    {
        $this->groupMember = $groupMember;

        return $this;
    }

    /**
     * Gets the group Desc.
     *
     * @return string
     */
    public function getGroupDesc(): string
    {
        return $this->groupDesc;
    }

    /**
     * Sets the group Desc.
     *
     * @param string $groupDesc
     * @return $this
     */
    public function setGroupDesc(string $groupDesc): Group
    {
        $this->groupDesc = $groupDesc;

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
                'name' => $this->getGroupName(),
                'tag' => $this->getGroupTag(),
                'image' => $this->getGroupImage(),
                'member' => $this->getGroupMember(),
                'desc' => $this->getGroupDesc(),
            ]
        );
    }
}
