<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Link\Models;

class Link extends \Ilch\Model
{
    /**
     * The id of the link.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The position of the link.
     *
     * @var int
     */
    protected $position = 0;

    /**
     * The name of the link.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The link of the link.
     *
     * @var string
     */
    protected $link = '';

    /**
     * The banner of the link.
     *
     * @var string
     */
    protected $banner = '';

    /**
     * The category of the link.
     *
     * @var int
     */
    protected $cat_id = 0;

    /**
     * The category of the link.
     *
     * @var string
     */
    protected $desc = '';

    /**
     * The category of the link.
     *
     * @var int
     */
    protected $hits = 0;

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Link
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['cat_id'])) {
            $this->setCatId($entries['cat_id']);
        }
        if (isset($entries['pos'])) {
            $this->setPosition($entries['pos']);
        }
        if (isset($entries['name'])) {
            $this->setName($entries['name']);
        }
        if (isset($entries['desc'])) {
            $this->setDesc($entries['desc']);
        }
        if (isset($entries['banner'])) {
            $this->setBanner($entries['banner']);
        }
        if (isset($entries['link'])) {
            $this->setLink($entries['link']);
        }
        if (isset($entries['hits'])) {
            $this->setHits($entries['hits']);
        }
        return $this;
    }

    /**
     * Gets the id of the link.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the link.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Link
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returns the position of the link.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Sets the position.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): Link
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Gets the name of the link.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the link.
     *
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Link
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the link of the link.
     *
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Sets the link of the link.
     *
     * @param string $link
     * @return $this
     */
    public function setLink(string $link): Link
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Gets the banner of the link.
     *
     * @return string
     */
    public function getBanner(): string
    {
        return $this->banner;
    }

    /**
     * Sets the banner of the link.
     *
     * @param string $banner
     * @return $this
     */
    public function setBanner(string $banner): Link
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Gets the category of the link.
     *
     * @return int
     */
    public function getCatId(): int
    {
        return $this->cat_id;
    }

    /**
     * Sets the category of the link.
     *
     * @param $cat
     * @return $this
     */
    public function setCatId($cat): Link
    {
        $this->cat_id = $cat;

        return $this;
    }

    /**
     * Gets the description of the link.
     *
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * Sets the description of the link.
     *
     * @param string $desc
     * @return $this
     */
    public function setDesc(string $desc): Link
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Gets the hits of the link.
     *
     * @return int
     */
    public function getHits(): int
    {
        return $this->hits;
    }

    /**
     * Sets the hits of the link.
     *
     * @param string $hits
     * @return $this
     */
    public function setHits(string $hits): Link
    {
        $this->hits = $hits;

        return $this;
    }

    /**
     * Sets the hits of the link.
     *
     * @param int $hit
     * @return $this
     */
    public function addHits(int $hit = 1): Link
    {
        $this->hits = $this->hits + $hit;

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
                'name'      => $this->getName(),
                'link'      => $this->getLink(),
                'banner'    => $this->getBanner(),
                'desc'      => $this->getDesc(),
                'cat_id'    => $this->getCatId(),
                'pos'       => $this->getPosition(),
                'hits'      => $this->getHits()
            ]
        );
    }
}
