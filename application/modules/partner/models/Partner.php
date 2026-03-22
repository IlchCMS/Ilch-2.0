<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Partner\Models;

class Partner extends \Ilch\Model
{
    /**
     * The id of the partner.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The name of the partner.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The link of the partner.
     *
     * @var string
     */
    protected $link = '';

    /**
     * The banner of the partner.
     *
     * @var string
     */
    protected $banner = '';

    /**
     * The link target of the entry.
     *
     * @var int
     */
    protected $target = 0;

    /**
     * The free of the entry.
     *
     * @var int
     */
    protected $free = 1;

    /**
     * @param array $entries
     * @return $this
     * @since 1.14.2
     */
    public function setByArray(array $entries): Partner
    {
        if (!empty($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (!empty($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (!empty($entries['link'])) {
            $this->setLink($entries['link']);
        }
        if (!empty($entries['banner'])) {
            $this->setBanner($entries['banner']);
        }
        if (!empty($entries['target'])) {
            $this->setTarget($entries['target']);
        }
        if (!empty($entries['setfree'])) {
            $this->setFree($entries['setfree']);
        }
        return $this;
    }

    /**
     * Gets the id of the partner.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the partner.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Partner
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the name of the partner.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the partner.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Partner
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the link of the partner.
     *
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Sets the link of the partner.
     *
     * @param string $link
     * @return $this
     */
    public function setLink(string $link): Partner
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Gets the banner of the partner.
     *
     * @return string
     */
    public function getBanner(): string
    {
        return $this->banner;
    }

    /**
     * Sets the banner of the partner.
     *
     * @param string $banner
     * @return $this
     */
    public function setBanner(string $banner): Partner
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Gets the link target of the entry.
     *
     * @return int
     */
    public function getTarget(): int
    {
        return $this->target;
    }

    /**
     * Set the link target of the entry.
     *
     * @param int $target
     * @return $this
     */
    public function setTarget(int $target): Partner
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Gets the free of the entry.
     *
     * @return int
     */
    public function getFree(): int
    {
        return $this->free;
    }

    /**
     * Set the free of the entry.
     *
     * @param int $free
     * @return $this
     */
    public function setFree(int $free): Partner
    {
        $this->free = $free;

        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.14.2
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'name' => $this->getName(),
                'link' => $this->getLink(),
                'banner' => $this->getBanner(),
                'target' => $this->getTarget(),
                'setfree' => $this->getFree()
            ]
        );
    }
}
