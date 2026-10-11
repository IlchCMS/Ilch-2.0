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
     */
    protected ?int $id = null;

    /**
     * Sort of the item.
     */
    protected ?int $sort = null;

    /**
     * Type of the item.
     */
    protected ?int $type = null;

    /**
     * Key of the item.
     */
    protected ?string $moduleKey = null;

    /**
     * Siteid of the item.
     */
    protected ?int $siteId = null;

    /**
     * Boxid of the item.
     */
    protected ?int $boxId = null;

    /**
     * Boxkey of the item.
     */
    protected ?string $boxKey = null;

    /**
     * MenuId of the item.
     */
    protected ?int $menuId = null;

    /**
     * ParentId of the item.
     */
    protected ?int $parentId = null;

    /**
     * Title of the item.
     */
    protected ?string $title = null;

    /**
     * Href of the item.
     */
    protected ?string $href = null;

    /**
     * target of the item (a link).
     *
     * Untenionally untyped: the value is stored without casting, null stays null
     * (legacy behavior, documented by the model tests).
     */
    protected mixed $target = null;

    /**
     * Access of the item.
     *
     * Untenionally untyped: the value is stored without casting, null stays null
     * (legacy behavior, documented by the model tests).
     */
    protected mixed $access = null;

    /**
     * Gets the id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id.
     *
     * @param int|float|string $id
     */
    public function setId(int|float|string $id): void
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the sort.
     *
     * @return int|null
     */
    public function getSort(): ?int
    {
        return $this->sort;
    }

    /**
     * Sets the sort.
     *
     * @param int|float|string $sort
     */
    public function setSort(int|float|string $sort): void
    {
        $this->sort = (int) $sort;
    }

    /**
     * Gets the type.
     *
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * Sets the type.
     *
     * @param int|float|string $type
     */
    public function setType(int|float|string $type): void
    {
        $this->type = (int) $type;
    }

    /**
     * Gets the siteid.
     *
     * @return int|null
     */
    public function getSiteId(): ?int
    {
        return $this->siteId;
    }

    /**
     * Sets the siteid.
     *
     * @param int|float|string $id
     */
    public function setSiteId(int|float|string $id): void
    {
        $this->siteId = (int) $id;
    }

    /**
     * Gets the boxid.
     *
     * @return int|null
     */
    public function getBoxId(): ?int
    {
        return $this->boxId;
    }

    /**
     * Sets the boxid.
     *
     * @param int|float|string $id
     */
    public function setBoxId(int|float|string $id): void
    {
        $this->boxId = (int) $id;
    }

    /**
     * Gets the box key.
     *
     * @return string|null
     */
    public function getBoxKey(): ?string
    {
        return $this->boxKey;
    }

    /**
     * Sets the box key.
     *
     * Accepts null (the database column is nullable); null is cast to an empty string.
     *
     * @param string|null $key
     */
    public function setBoxKey(?string $key): void
    {
        $this->boxKey = (string) $key;
    }

    /**
     * Gets the module key.
     *
     * @return string|null
     */
    public function getModuleKey(): ?string
    {
        return $this->moduleKey;
    }

    /**
     * Sets the module key.
     *
     * Accepts null (the database column is nullable); null is cast to an empty string.
     *
     * @param string|null $key
     */
    public function setModuleKey(?string $key): void
    {
        $this->moduleKey = (string) $key;
    }

    /**
     * Gets the menu id.
     *
     * @return int|null
     */
    public function getMenuId(): ?int
    {
        return $this->menuId;
    }

    /**
     * Sets the menu id.
     *
     * @param int|float|string $id
     */
    public function setMenuId(int|float|string $id): void
    {
        $this->menuId = (int) $id;
    }

    /**
     * Gets the parent id.
     *
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * Sets the parent id.
     *
     * @param int|float|string $id
     */
    public function setParentId(int|float|string $id): void
    {
        $this->parentId = (int) $id;
    }

    /**
     * Gets the title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Gets the href.
     *
     * @return string|null
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * Sets the href.
     *
     * Accepts null (the database column is nullable); null is cast to an empty string.
     *
     * @param string|null $href
     */
    public function setHref(?string $href): void
    {
        $this->href = (string) $href;
    }

    /**
     * Get the target of a link.
     *
     * @return mixed
     */
    public function getTarget(): mixed
    {
        return $this->target;
    }

    /**
     * Sets the target of a link.
     *
     * The value is stored without casting, null stays null.
     *
     * @param mixed $target
     */
    public function setTarget(mixed $target): void
    {
        $this->target = $target;
    }

    /**
     * Checks if the item is any link
     *
     * @return bool
     */
    public function isLink(): bool
    {
        return in_array($this->getType(), [self::TYPE_LINK, self::TYPE_PAGE_LINK, self::TYPE_MODULE_LINK]);
    }

    /**
     * Checks if the item is a module link
     *
     * @return bool
     */
    public function isModuleLink(): bool
    {
        return $this->getType() === self::TYPE_MODULE_LINK;
    }

    /**
     * Checks if the item is a page link
     *
     * @return bool
     */
    public function isPageLink(): bool
    {
        return $this->getType() === self::TYPE_PAGE_LINK;
    }

    /**
     * Checks if the item is a box
     *
     * @return bool
     */
    public function isBox(): bool
    {
        return $this->getType() === self::TYPE_BOX;
    }

    /**
     * Checks if the item is a menu
     *
     * @return bool
     */
    public function isMenu(): bool
    {
        return $this->getType() === self::TYPE_MENU;
    }

    /**
     * Gets the access id.
     *
     * @return mixed
     */
    public function getAccess(): mixed
    {
        return $this->access;
    }

    /**
     * Sets the access id.
     *
     * The value is stored without casting, null stays null.
     *
     * @param mixed $access
     */
    public function setAccess(mixed $access): void
    {
        $this->access = $access;
    }
}
