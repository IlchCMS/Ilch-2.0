<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Models;

use Ilch\Model;

/**
 * The rank model class.
 *
 * @package ilch
 */
class Rank extends Model
{
    /**
     * The id of the item.
     *
     * @var int
     */
    protected $id;

    /**
     * The title of the rank.
     *
     * @var string
     */
    protected $title;
    
    /**
     * The number of posts needed for this rank.
     *
     * @var int
     */
    protected $posts;

    /**
     * Sets the id.
     *
     * @param int|null $id
     */
    public function setId(?int $id)
    {
        $this->id = $id;
    }

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
     * Sets the title.
     *
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
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
     * Sets the number of posts needed.
     *
     * @param int $posts
     */
    public function setPosts(int $posts)
    {
        $this->posts = $posts;
    }

    /**
     * Gets the number of posts needed.
     *
     * @return int
     */
    public function getPosts(): int
    {
        return $this->posts;
    }
}
