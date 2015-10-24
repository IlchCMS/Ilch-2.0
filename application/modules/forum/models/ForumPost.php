<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Models;

class ForumPost extends \Ilch\Model
{
    /**
     * The id of the image.
     *
     * @var integer
     */
    protected $id;

    /**
     * The fileId of the file.
     *
     * @var string
     */
    protected $topic_id;

    /**
     * Title of the file.
     *
     * @var string
     */
    protected $topic_title;

    /**
     * Description of the file.
     *
     * @var string
     */
    protected $text;

    /**
     * The cat of the file.
     *
     * @var string
     */
    protected $cat;

    /**
     * The visits of the file.
     *
     * @var string
     */
    protected $visits;

    /**
     * The visits of the file.
     *
     * @var string
     */
    protected $forum_id;

    /**
     * The visits of the file.
     *
     * @var string
     */
    protected $read;

    /**
     * The imageUrl of the file.
     *
     * @var string
     */
    protected $page;

    /**
     * The imageUrl of the file.
     *
     * @var string
     */
    protected $avatar;

    /**
     * The imageUrl of the file.
     *
     * @var string
     */
    protected $signature;

    /**
     * The imageUrl of the file.
     *
     * @var string
     */
    protected $user_id;

    /**
     * The imageUrl of the file.
     *
     * @var string
     */
    protected $date_created;

    /**
     * The imageUrl of the file.
     *
     * @var string
     */
    protected $autor;

   /**
     * Gets the id of the file.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the fileId of the file.
     *
     * @return string
     */
    public function getTopicId()
    {
        return $this->topic_id;
    }

    /**
     * Gets the fileThumb of the file.
     *
     * @return string
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Gets the file title.
     *
     * @return string
     */
    public function getTopicTitle()
    {
        return $this->topic_title;
    }

    /**
     * Gets the file desc.
     *
     * @return string
     */
    public function getTopicDesc()
    {
        return $this->topic_desc;
    }

    /**
     * Gets the file desc.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    /**
     * Gets the file image.
     *
     * @return string
     */
    public function getTopicImage()
    {
        return $this->topic_image;
    }

    /**
     * Gets the cat of the file.
     *
     * @return string
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * Gets the visits of the file.
     *
     * @return string
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Gets the visits of the file.
     *
     * @return string
     */
    public function getForumId()
    {
        return $this->forum_id;
    }

    /**
     * Gets the FileUrl of the file.
     *
     * @return string
     */
    public function getTopicUrl()
    {
        return $this->topic_url;
    }

    /**
     * Gets the id of the file.
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Gets the FileUrl of the file.
     *
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Gets the FileUrl of the file.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Gets the FileUrl of the file.
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Returns the date_created \Ilch\Date of the user.
     *
     * @return \Ilch\Date
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Returns the date_created \Ilch\Date of the user.
     *
     * @return \Ilch\Date
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Sets the id of the file.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Sets the fileId of the file.
     *
     * @param string $file_id
     */
    public function setTopicId($topic_id)
    {
        $this->topic_id = (string)$topic_id;
    }

    /**
     * Sets the fileThumb of the file.
     *
     * @param string $fileThumb
     */
    public function setRead($read)
    {
        $this->read = $read;
    }

    /**
     * Sets the title.
     *
     * @param string $fileTitle
     */
    public function setTopicTitle($topicTitle)
    {
        $this->topic_title = (string) $topicTitle;
    }

    /**
     * Sets the image.
     *
     * @param string $fileImage
     */
    public function setTopicImage($topicImage)
    {
        $this->topic_image = (string) $topicImage;
    }

    /**
     * Sets the desc.
     *
     * @param string $fileDesc
     */
    public function setTopicDesc($topicDesc)
    {
        $this->topic_desc = (string) $topicDesc;
    }

    /**
     * Sets the desc.
     *
     * @param string $fileDesc
     */
    public function setText($text)
    {
        $this->text = (string) $text;
    }

    /**
     * Sets the cat of the file.
     *
     * @param string $cat
     */
    public function setCat($cat)
    {
        $this->cat = (string)$cat;
    }

    /**
     * Sets the visits of the file.
     *
     * @param string $visits
     */
    public function setVisits($visits)
    {
        $this->visits = (string)$visits;
    }

    /**
     * Sets the visits of the file.
     *
     * @param string $visits
     */
    public function setForumId($forumId)
    {
        $this->forum_id = (int)$forumId;
    }

    /**
     * Sets the fileUrl of the file.
     *
     * @param string $fileUrl
     */
    public function setTopicUrl($topicUrl)
    {
        $this->topic_url = (string)$topicUrl;
    }

    /**
     * Sets the id of the file.
     *
     * @param integer $id
     */
    public function setUserId($userId)
    {
        $this->user_id = (int)$userId;
    }

    /**
     * Sets the fileUrl of the file.
     *
     * @param string $fileUrl
     */
    public function setPage($page)
    {
        $this->page = (string)$page;
    }

    /**
     * Sets the fileUrl of the file.
     *
     * @param string $fileUrl
     */
    public function setAvatar($avatar)
    {
        $this->avatar = (string)$avatar;
    }

    /**
     * Sets the fileUrl of the file.
     *
     * @param string $fileUrl
     */
    public function setSignature($signature)
    {
        $this->signature = (string)$signature;
    }

    /**
     * Saves the date_created \Ilch\Date of the user.
     *
     * @param \Ilch\Date $dateCreated
     * @return User
     */
    public function setDateCreated($dateCreated)
    {
        $this->date_created = $dateCreated;

        return $this;
    }

    /**
     * Saves the date_created \Ilch\Date of the user.
     *
     * @param \Ilch\Date $dateCreated
     * @return User
     */
    public function setAutor($autor)
    {
        $this->autor = $autor;

        return $this;
    }
}