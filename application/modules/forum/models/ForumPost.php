<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

class ForumPost extends \Ilch\Model
{
    /**
     * The id of the post.
     *
     * @var integer
     */
    protected $id;

    /**
     * The topic d of the post.
     *
     * @var integer
     */
    protected $topic_id;

    /**
     * The title of the post.
     *
     * @var string
     */
    protected $topic_title;

    /**
     * The text of the post.
     *
     * @var string
     */
    protected $text;

    /**
     * The cat of the post.
     *
     * @var integer
     */
    protected $cat;

    /**
     * The visits of the post.
     *
     * @var string
     */
    protected $visits;

    /**
     * The forum id of the post.
     *
     * @var integer
     */
    protected $forum_id;

    /**
     * The read of the post.
     *
     * @var string
     */
    protected $read;

    /**
     * The page of the post.
     *
     * @var string
     */
    protected $page;

    /**
     * The avatar of the post.
     *
     * @var string
     */
    protected $avatar;

    /**
     * The imageUrl of the post.
     *
     * @var string
     */
    protected $signature;

    /**
     * The user id of the post.
     *
     * @var integer
     */
    protected $user_id;

    /**
     * The date created of the post.
     *
     * @var string
     */
    protected $date_created;

    /**
     * The autor of the post.
     *
     * @var string
     */
    protected $autor;

    /**
     * The autor all of the post.
     *
     * @var string
     */
    protected $autorallpost;

   /**
     * Gets the id of the post.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the topic id of the post.
     *
     * @return string
     */
    public function getTopicId()
    {
        return $this->topic_id;
    }

    /**
     * Gets the read of the post.
     *
     * @return string
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Gets the title of the post.
     *
     * @return string
     */
    public function getTopicTitle()
    {
        return $this->topic_title;
    }

    /**
     * Gets the desc of the post.
     *
     * @return string
     */
    public function getTopicDesc()
    {
        return $this->topic_desc;
    }

    /**
     * Gets the file text of the post.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    /**
     * Gets the image of the post.
     *
     * @return string
     */
    public function getTopicImage()
    {
        return $this->topic_image;
    }

    /**
     * Gets the cat of the post.
     *
     * @return string
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Gets the visits of the post.
     *
     * @return string
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Gets the forum id of the post.
     *
     * @return string
     */
    public function getForumId()
    {
        return $this->forum_id;
    }

    /**
     * Gets the topic url of the post.
     *
     * @return string
     */
    public function getTopicUrl()
    {
        return $this->topic_url;
    }

    /**
     * Gets the user id of the post.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Gets the page of the post.
     *
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Gets the avatar of the post.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Gets the signature of the post.
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Returns the date created \Ilch\Date of the post.
     *
     * @return \Ilch\Date
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Returns the autor of the post.
     *
     * @return string
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Returns the autor all of the post.
     *
     * @return string
     */
    public function getAutorAllPost()
    {
        return $this->autorallpost;
    }

    /**
     * Sets the id of the post.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Sets the topic id of the post.
     *
     * @param string $topic_id
     * @return $this
     */
    public function setTopicId($topic_id)
    {
        $this->topic_id = (string) $topic_id;

        return $this;
    }

    /**
     * Sets the read of the post.
     *
     * @param string $read
     * @return $this
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Sets the title of the post.
     *
     * @param string $topicTitle
     * @return $this
     */
    public function setTopicTitle($topicTitle)
    {
        $this->topic_title = (string) $topicTitle;

        return $this;
    }

    /**
     * Sets the image of the post.
     *
     * @param string $topicImage
     * @return $this
     */
    public function setTopicImage($topicImage)
    {
        $this->topic_image = (string) $topicImage;

        return $this;
    }

    /**
     * Sets the desc of the post.
     *
     * @param string $topicDesc
     * @return $this
     */
    public function setTopicDesc($topicDesc)
    {
        $this->topic_desc = (string) $topicDesc;

        return $this;
    }

    /**
     * Sets the text of the post.
     *
     * @param string $text
     * @return $this
     */
    public function setText($text)
    {
        $this->text = (string) $text;

        return $this;
    }

    /**
     * Sets the cat of the post.
     *
     * @param string $cat
     * @return $this
     */
    public function setCat($cat)
    {
        $this->cat = (string)$cat;

        return $this;
    }

    /**
     * Sets the visits of the post.
     *
     * @param string $visits
     * @return $this
     */
    public function setVisits($visits)
    {
        $this->visits = (string) $visits;

        return $this;
    }

    /**
     * Sets the forum id of the post.
     *
     * @param int $forumId
     * @return $this
     */
    public function setForumId($forumId)
    {
        $this->forum_id = (int) $forumId;

        return $this;
    }

    /**
     * Sets the topic url of the post.
     *
     * @param string $topicUrl
     * @return $this
     */
    public function setTopicUrl($topicUrl)
    {
        $this->topic_url = (string) $topicUrl;

        return $this;
    }

    /**
     * Sets the user id of the post.
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->user_id = (int) $userId;

        return $this;
    }

    /**
     * Sets the page of the post.
     *
     * @param string $page
     * @return $this
     */
    public function setPage($page)
    {
        $this->page = (string) $page;

        return $this;
    }

    /**
     * Sets the avatar of the post.
     *
     * @param string $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = (string) $avatar;

        return $this;
    }

    /**
     * Sets the signature of the post.
     *
     * @param string $signature
     * @return $this
     */
    public function setSignature($signature)
    {
        $this->signature = (string) $signature;

        return $this;
    }

    /**
     * Saves the date created \Ilch\Date of the post.
     *
     * @param \Ilch\Date $dateCreated
     * @return $this
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;

        return $this;
    }

    /**
     * Saves the $autor of the post.
     *
     * @param $autor
     * @return $this
     */
    public function setAutor($autor)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Saves the autor all of the post.
     *
     * @param $autorAllPost
     * @return $this
     */
    public function setAutorAllPost($autorAllPost)
    {
        $this->autorallpost = $autorAllPost;

        return $this;
    }

}