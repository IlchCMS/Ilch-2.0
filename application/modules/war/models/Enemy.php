<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\War\Models;

class Enemy extends \Ilch\Model
{
    /**
     * The id.
     *
     * @var integer
     */
    protected $id;

    /**
     * The Enemy Name.
     *
     * @var string
     */
    protected $enemyName;

    /**
     * The Enemy Tag.
     *
     * @var string
     */
    protected $enemyTag;

    /**
     * The Enemy Homepage.
     *
     * @var string
     */
    protected $enemyHomepage;

    /**
     * The Enemy Image.
     *
     * @var string
     */
    protected $enemyImage;

    /**
     * The Enemy ImageThumb.
     *
     * @var string
     */
    protected $enemyImageThumb;

    /**
     * The Enemy Land.
     *
     * @var string
     */
    protected $enemyLand;

    /**
     * The Enemy Contact Name.
     *
     * @var string
     */
    protected $enemyContactName;

    /**
     * The Enemy Contact Email.
     *
     * @var string
     */
    protected $enemyContactEmail;

    /**
     * Gets the id of the group.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the group.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Gets the enemy name.
     *
     * @return string
     */
    public function getEnemyName()
    {
        return $this->enemyName;
    }

    /**
     * Sets the enemy name.
     *
     * @param string $enemyName
     */
    public function setEnemyName($enemyName)
    {
        $this->enemyName = (string)$enemyName;
    }

    /**
     * Gets the enemy tag.
     *
     * @return string
     */
    public function getEnemyTag()
    {
        return $this->enemyTag;
    }

    /**
     * Sets the enemy tag.
     *
     * @param string $enemyTag
     */
    public function setEnemyTag($enemyTag)
    {
        $this->enemyTag = (string)$enemyTag;
    }

    /**
     * Gets the enemy Homepage.
     *
     * @return string
     */
    public function getEnemyHomepage()
    {
        return $this->enemyHomepage;
    }

    /**
     * Sets the enemy Homepage.
     *
     * @param string $enemyHomepage
     */
    public function setEnemyHomepage($enemyHomepage)
    {
        $this->enemyHomepage = (string)$enemyHomepage;
    }

    /**
     * Gets the enemy Image.
     *
     * @return string
     */
    public function getEnemyImage()
    {
        return $this->enemyImage;
    }

    /**
     * Sets the enemy Image.
     *
     * @param string $enemyImage
     */
    public function setEnemyImage($enemyImage)
    {
        $this->enemyImage = (string)$enemyImage;
    }

    /**
     * Gets the enemy ImageThumb.
     *
     * @return string
     */
    public function getEnemyImageThumb()
    {
        return $this->enemyImageThumb;
    }

    /**
     * Sets the enemy ImageThumb.
     *
     * @param string $enemyImageThumb
     */
    public function setEnemyImageThumb($enemyImageThumb)
    {
        $this->enemyImageThumb = (string)$enemyImageThumb;
    }

    /**
     * Gets the enemy Land.
     *
     * @return string
     */
    public function getEnemyLand()
    {
        return $this->enemyLand;
    }

    /**
     * Sets the enemy Land.
     *
     * @param string $enemyLand
     */
    public function setEnemyLand($enemyLand)
    {
        $this->enemyLand = (string)$enemyLand;
    }

    /**
     * Gets the enemy Contact Name.
     *
     * @return string
     */
    public function getEnemyContactName()
    {
        return $this->enemyContactName;
    }

    /**
     * Sets the enemy Contact Name.
     *
     * @param string $enemyContactName
     */
    public function setEnemyContactName($enemyContactName)
    {
        $this->enemyContactName = (string)$enemyContactName;
    }

    /**
     * Gets the enemy Contact Email.
     *
     * @return string
     */
    public function getEnemyContactEmail()
    {
        return $this->enemyContactEmail;
    }

    /**
     * Sets the enemy Contact Email.
     *
     * @param string $enemyContactEmail
     */
    public function setEnemyContactEmail($enemyContactEmail)
    {
        $this->enemyContactEmail = (string)$enemyContactEmail;
    }
}
