<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Models;

class Category extends \Ilch\Mapper
{



    /**
     * @param int $id
     * @param string $title
     * @param string $read_access
     */
    public function __construct(
        private int $id = 0,
        private string $title = '',
        private string $read_access = '')
    {
    }

    /**
     * @param array $entries
     * @return $this
     * @since 1.9.0
     */
    public function setByArray(array $entries): Category
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['title'])) {
            $this->setTitle($entries['title']);
        }
        if (isset($entries['read_access'])) {
            $this->setReadAccess($entries['read_access']);
        }
        if (isset($entries['read_access_all'])) {
            if ($entries['read_access_all']) {
                $this->setReadAccess('all');
            }
        }

        return $this;
    }

    /**
     * Gets the category id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the category.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Category
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the title of the category.
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the title of the category.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): Category
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get the value for read_access.
     *
     * @return string
     */
    public function getReadAccess(): ?string
    {
        return $this->read_access;
    }

    /**
     * Set the value for read_access.
     *
     * @param string $read_access
     * @return $this
     */
    public function setReadAccess(string $read_access): Category
    {
        $this->read_access = $read_access;
        return $this;
    }

    /**
     * @param bool $withId
     * @return array
     * @since 1.9.0
     */
    public function getArray(bool $withId = true): array
    {
        return array_merge(
            ($withId ? ['id' => $this->getId()] : []),
            [
                'title'             => $this->getTitle(),
                'read_access_all'   => ($this->getReadAccess() === 'all' ? 1 : 0),
            ]
        );
    }
}
