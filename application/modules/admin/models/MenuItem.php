<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

class MenuItem extends \Ilch\Model
{
    public const TYPE_MENU = 0;
    public const TYPE_LINK = 1;
    public const TYPE_PAGE_LINK = 2;
    public const TYPE_MODULE_LINK = 3;
    public const TYPE_BOX = 4;

    /**
     * Id of the item.
     *
     * @var int
     */
    protected $id;

    /**
     * Sort of the item.
     *
     * @var int
     */
    protected $sort;

    /**
     * Type of the item.
     *
     * @var int
     */
    protected $type;

    /**
     * Key of the item.
     *
     * @var string
     */
    protected $moduleKey;

    /**
     * Siteid of the item.
     *
     * @var int
     */
    protected $siteId;

    /**
     * Boxid of the item.
     *
     * @var int
     */
    protected $boxId;

    /**
     * Boxid of the item.
     *
     * @var string
     */
    protected $boxKey;

    /**
     * MenuId of the item.
     *
     * @var int
     */
    protected $menuId;

    /**
     * ParentId of the item.
     *
     * @var int
     */
    protected $parentId;

    /**
     * Title of the item.
     *
     * @var string
     */
    protected $title;

    /**
     * Href of the item.
     *
     * @var string
     */
    protected $href;

    /**
     * target of the item (a link).
     *
     * @var string
     */
    protected $target;

    /**
     * Access of the item.
     *
     * @var string
     */
    protected $access;

    /**
     * Gets the id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the sort.
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Sets the sort.
     *
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = (int)$sort;
    }

    /**
     * Gets the type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = (int)$type;
    }

    /**
     * Gets the siteid.
     *
     * @return int
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Sets the siteid.
     *
     * @param int $id
     */
    public function setSiteId($id)
    {
        $this->siteId = (int)$id;
    }

    /**
     * Gets the boxid.
     *
     * @return int
     */
    public function getBoxId()
    {
        return $this->boxId;
    }

    /**
     * Sets the boxid.
     *
     * @param int $id
     */
    public function setBoxId($id)
    {
        $this->boxId = (int)$id;
    }

    /**
     * Gets the box id.
     *
     * @return string
     */
    public function getBoxKey()
    {
        return $this->boxKey;
    }

    /**
     * Sets the box id.
     *
     * @param string $key
     */
    public function setBoxKey($key)
    {
        $this->boxKey = (string)$key;
    }

    /**
     * Gets the module key.
     *
     * @return string
     */
    public function getModuleKey()
    {
        return $this->moduleKey;
    }

    /**
     * Sets the module key.
     *
     * @param string $key
     */
    public function setModuleKey($key)
    {
        $this->moduleKey = (string)$key;
    }

    /**
     * Gets the menu id.
     *
     * @return int
     */
    public function getMenuId()
    {
        return $this->menuId;
    }

    /**
     * Sets the menu id.
     *
     * @param int $id
     */
    public function setMenuId($id)
    {
        $this->menuId = (int) $id;
    }

    /**
     * Gets the parent id.
     *
     * @return int
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Sets the parent id.
     *
     * @param int $id
     */
    public function setParentId($id)
    {
        $this->parentId = (int) $id;
    }

    /**
     * Gets the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
    }

    /**
     * Gets the href.
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Sets the href.
     *
     * @param string $href
     */
    public function setHref($href)
    {
        $this->href = (string) $href;
    }

    /**
     * Get the target of a link.
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Sets the target of a link.
     *
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Checks if the item is any link
     *
     * @return bool
     */
    public function isLink()
    {
        return in_array($this->getType(), [self::TYPE_LINK, self::TYPE_PAGE_LINK, self::TYPE_MODULE_LINK]);
    }

    /**
     * Checks if the item is a module link
     *
     * @return bool
     */
    public function isModuleLink()
    {
        return $this->getType() === self::TYPE_MODULE_LINK;
    }

    /**
     * Checks if the item is a page link
     *
     * @return bool
     */
    public function isPageLink()
    {
        return $this->getType() === self::TYPE_PAGE_LINK;
    }

    /**
     * Checks if the item is a box
     *
     * @return bool
     */
    public function isBox()
    {
        return $this->getType() === self::TYPE_BOX;
    }

    /**
     * Checks if the item is a menu
     *
     * @return bool
     */
    public function isMenu()
    {
        return $this->getType() === self::TYPE_MENU;
    }

    /**
     * Gets the access id.
     *
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Sets the access id.
     *
     * @param $access
     */
    public function setAccess($access)
    {
        $this->access = $access;
    }
}
