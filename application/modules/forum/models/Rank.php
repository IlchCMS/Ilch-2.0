<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

/**
 * The rank model class.
 *
 * @package ilch
 */
class Rank extends \Ilch\Model
{
    /**
     * The id of the item.
     *
     * @var integer
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
     * @var integer
     */
    protected $posts;

    /**
     * Sets the id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * Sets the title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * Sets the number of posts needed.
     *
     * @param integer $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * Gets the number of posts needed.
     *
     * @return integer
     */
    public function getPosts()
    {
        return $this->posts;
    }
}