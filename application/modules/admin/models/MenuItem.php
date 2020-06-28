<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class MenuItem extends \Ilch\Model
{
    const TYPE_MENU = 0;
    const TYPE_LINK = 1;
    const TYPE_PAGE_LINK = 2;
    const TYPE_MODULE_LINK = 3;
    const TYPE_BOX = 4;

    /**
     * Id of the item.
     *
     * @var integer
     */
    protected $id;
    
    /**
     * Sort of the item.
     *
     * @var integer
     */
    protected $sort;

    /**
     * Type of the item.
     *
     * @var integer
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
     * @var integer
     */
    protected $siteId;
    
    /**
     * Boxid of the item.
     *
     * @var integer
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
     * @var integer
     */
    protected $menuId;

    /**
     * ParentId of the item.
     *
     * @var integer
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
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the sort.
     *
     * @return integer
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * Sets the sort.
     *
     * @param integer $sort
     */
    public function setSort($sort)
    {
        $this->sort = (int)$sort;
    }

    /**
     * Gets the type.
     *
     * @return integer
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param integer $type
     */
    public function setType($type)
    {
        $this->type = (int)$type;
    }

    /**
     * Gets the siteid.
     *
     * @return integer
     */
    public function getSiteId(): int
    {
        return $this->siteId;
    }

    /**
     * Sets the siteid.
     *
     * @param integer $id
     */
    public function setSiteId($id)
    {
        $this->siteId = (int)$id;
    }
    
    /**
     * Gets the boxid.
     *
     * @return integer
     */
    public function getBoxId(): int
    {
        return $this->boxId;
    }

    /**
     * Sets the boxid.
     *
     * @param integer $id
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
    public function getBoxKey(): string
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
    public function getModuleKey(): string
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
     * @return integer
     */
    public function getMenuId(): int
    {
        return $this->menuId;
    }

    /**
     * Sets the menu id.
     *
     * @param integer $id
     */
    public function setMenuId($id)
    {
        $this->menuId = (int) $id;
    }

    /**
     * Gets the parent id.
     *
     * @return integer
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * Sets the parent id.
     *
     * @param integer $id
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
    public function getTitle(): string
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
    public function getHref(): string
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
    public function getTarget(): string
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
     * @return boolean
     */
    public function isLink(): bool
    {
        return in_array($this->getType(), [self::TYPE_LINK, self::TYPE_PAGE_LINK, self::TYPE_MODULE_LINK]);
    }

    /**
     * Checks if the item is a module link
     *
     * @return boolean
     */
    public function isModuleLink(): bool
    {
        return $this->getType() === self::TYPE_MODULE_LINK;
    }

    /**
     * Checks if the item is a page link
     *
     * @return boolean
     */
    public function isPageLink(): bool
    {
        return $this->getType() === self::TYPE_PAGE_LINK;
    }

    /**
     * Checks if the item is a box
     *
     * @return boolean
     */
    public function isBox(): bool
    {
        return $this->getType() === self::TYPE_BOX;
    }

    /**
     * Checks if the item is a menu
     *
     * @return boolean
     */
    public function isMenu(): bool
    {
        return $this->getType() === self::TYPE_MENU;
    }

    /**
     * Gets the access id.
     *
     * @return string
     */
    public function getAccess(): string
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
