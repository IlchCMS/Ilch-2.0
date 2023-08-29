<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Privacy\Models;

class Privacy extends \Ilch\Model
{
    /**
     * The id of the privacy.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The title of the privacy.
     *
     * @var string
     */
    protected $title = '';

    /**
     * The urltitle of the privacy.
     *
     * @var string
     */
    protected $urltitle = '';

    /**
     * The url of the privacy.
     *
     * @var string
     */
    protected $url = '';

    /**
     * The text of the privacy.
     *
     * @var string
     */
    protected $text = '';

    /**
     * The show of the privacy.
     *
     * @var bool
     */
    protected $show = false;

    /**
     * The show of the privacy.
     *
     * @var bool
     */
    protected $position = 0;

    /**
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Privacy
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['urltitle'])) {
            $this->setUrlTitle($entries['urltitle']);
        }
        if (isset($entries['url'])) {
            $this->setUrl($entries['url']);
        }
        if (isset($entries['text'])) {
            $this->setText($entries['text']);
        }
        if (isset($entries['show'])) {
            $this->setShow($entries['show']);
        }
        if (isset($entries['position'])) {
            $this->setPosition($entries['position']);
        }
        return $this;
    }

    /**
     * Gets the id of the privacy.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the privacy.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Privacy
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the title of the privacy.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets the title of the privacy.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Privacy
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Gets the urltitle of the privacy.
     *
     * @return string
     */
    public function getUrlTitle(): string
    {
        return $this->urltitle;
    }

    /**
     * Sets the urltitle of the privacy.
     *
     * @param string $urltitle
     * @return $this
     */
    public function setUrlTitle(string $urltitle): Privacy
    {
        $this->urltitle = $urltitle;

        return $this;
    }

    /**
     * Gets the url of the privacy.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Sets the url of the privacy.
     *
     * @param string $url
     * @return $this
     */
    public function setURL(string $url): Privacy
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Gets the text of the privacy.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Sets the text of the privacy.
     *
     * @param string $text
     * @return $this
     */
    public function setText(string $text): Privacy
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Gets the show of the privacy.
     *
     * @return bool
     */
    public function getShow(): bool
    {
        return $this->show;
    }

    /**
     * Sets the show of the privacy.
     *
     * @param bool $show
     * @return $this
     */
    public function setShow(bool $show): Privacy
    {
        $this->show = $show;

        return $this;
    }

    /**
     * Gets the position of the privacy.
     *
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Sets the position of the privacy.
     *
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): Privacy
    {
        $this->position = $position;

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
                'title'        => $this->getTitle(),
                'urltitle'     => $this->getUrlTitle(),
                'url'          => $this->getUrl(),
                'text'         => $this->getText(),
                'show'         => $this->getShow(),
                'position'     => $this->getPosition(),
            ]
        );
    }
}
