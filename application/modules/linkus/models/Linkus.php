<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Linkus\Models;

class Linkus extends \Ilch\Model
{
    /**
     * The id of the linkus.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The title of the linkus.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The banner of the linkus.
     *
     * @var string
     */
    protected $banner = '';

    /**
     * @param array $entries
     * @return $this
     * @since 1.7.3
     */
    public function setByArray(array $entries): Linkus
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['banner'])) {
            $this->setBanner($entries['banner']);
        }

        return $this;
    }

    /**
     * Gets the id of the linkus.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the linkus.
     *
     * @param int $id
     * @return this
     */
    public function setId(int $id): Linkus
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the title of the linkus.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the linkus.
     *
     * @param string $title
     * @return this
     */
    public function setTitle(string $title): Linkus
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the banner of the linkus.
     *
     * @return string
     */
    public function getBanner(): string
    {
        return $this->banner;
    }

    /**
     * Sets the banner of the linkus.
     *
     * @param string $banner
     * @return this
     */
    public function setBanner(string $banner): Linkus
    {
        $this->banner = $banner;

        return $this;
    }

    /**
     * Gets the Array of Model.
     *
     * @param bool $withId
     * @return array
     * @since 1.7.3
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'title' => $this->getTitle(),
                'banner' => $this->getBanner()
            ]
        );
    }
}
