<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

/**
 * The menu item model class.
 */
class MenuItem extends \Ilch\Model
{
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
     * Gets the id.
     *
     * @return integer
     */
    public function getId()
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
    public function getSort()
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param integer $id
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
    public function getSiteId()
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
    public function getBoxId()
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
     * @return integer
     */
    public function getMenuId()
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
    public function getParentId()
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
}
