<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\War\Models;

use Ilch\Model;

class Enemy extends Model
{
    /**
     * The id.
     *
     * @var int
     */
    protected $id = 0;

    /**
     * The Enemy Name.
     *
     * @var string
     */
    protected $enemyName = '';

    /**
     * The Enemy Tag.
     *
     * @var string
     */
    protected $enemyTag = '';

    /**
     * The Enemy Homepage.
     *
     * @var string
     */
    protected $enemyHomepage = '';

    /**
     * The Enemy Image.
     *
     * @var string
     */
    protected $enemyImage = '';

    /**
     * The Enemy ImageThumb.
     *
     * @var string
     */
    protected $enemyImageThumb = '';

    /**
     * The Enemy Land.
     *
     * @var string
     */
    protected $enemyLand = '';

    /**
     * The Enemy Contact Name.
     *
     * @var string
     */
    protected $enemyContactName = '';

    /**
     * The Enemy Contact Email.
     *
     * @var string
     */
    protected $enemyContactEmail = '';

    /**
     * Sets Model by Array.
     *
     * @param array $entries
     * @return $this
     */
    public function setByArray(array $entries): Enemy
    {
        if (isset($entries['id'])) {
            $this->setId($entries['id']);
        }
        if (isset($entries['name'])) {
            $this->setEnemyName($entries['name']);
        }
        if (isset($entries['tag'])) {
            $this->setEnemyTag($entries['tag']);
        }
        if (isset($entries['image'])) {
            $this->setEnemyImage($entries['image']);
        }
        if (isset($entries['homepage'])) {
            $this->setEnemyHomepage($entries['homepage']);
        }
        if (isset($entries['contact_name'])) {
            $this->setEnemyContactName($entries['contact_name']);
        }
        if (isset($entries['contact_email'])) {
            $this->setEnemyContactEmail($entries['contact_email']);
        }

        return $this;
    }

    /**
     * Gets the id of the group.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the group.
     *
     * @param int $id
     * @return $this
     */
    public function setId(int $id): Enemy
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the enemy name.
     *
     * @return string
     */
    public function getEnemyName(): string
    {
        return $this->enemyName;
    }

    /**
     * Sets the enemy name.
     *
     * @param string $enemyName
     * @return $this
     */
    public function setEnemyName(string $enemyName): Enemy
    {
        $this->enemyName = $enemyName;

        return $this;
    }

    /**
     * Gets the enemy tag.
     *
     * @return string
     */
    public function getEnemyTag(): string
    {
        return $this->enemyTag;
    }

    /**
     * Sets the enemy tag.
     *
     * @param string $enemyTag
     * @return $this
     */
    public function setEnemyTag(string $enemyTag): Enemy
    {
        $this->enemyTag = $enemyTag;

        return $this;
    }

    /**
     * Gets the enemy Homepage.
     *
     * @return string
     */
    public function getEnemyHomepage(): string
    {
        return $this->enemyHomepage;
    }

    /**
     * Sets the enemy Homepage.
     *
     * @param string $enemyHomepage
     * @return $this
     */
    public function setEnemyHomepage(string $enemyHomepage): Enemy
    {
        $this->enemyHomepage = $enemyHomepage;

        return $this;
    }

    /**
     * Gets the enemy Image.
     *
     * @return string
     */
    public function getEnemyImage(): string
    {
        return $this->enemyImage;
    }

    /**
     * Sets the enemy Image.
     *
     * @param string $enemyImage
     * @return $this
     */
    public function setEnemyImage(string $enemyImage): Enemy
    {
        $this->enemyImage = $enemyImage;

        return $this;
    }

    /**
     * Gets the enemy ImageThumb.
     *
     * @return string
     */
    public function getEnemyImageThumb(): string
    {
        return $this->enemyImageThumb;
    }

    /**
     * Sets the enemy ImageThumb.
     *
     * @param string $enemyImageThumb
     * @return $this
     */
    public function setEnemyImageThumb(string $enemyImageThumb): Enemy
    {
        $this->enemyImageThumb = $enemyImageThumb;

        return $this;
    }

    /**
     * Gets the enemy Land.
     *
     * @return string
     */
    public function getEnemyLand(): string
    {
        return $this->enemyLand;
    }

    /**
     * Sets the enemy Land.
     *
     * @param string $enemyLand
     * @return $this
     */
    public function setEnemyLand(string $enemyLand): Enemy
    {
        $this->enemyLand = $enemyLand;

        return $this;
    }

    /**
     * Gets the enemy Contact Name.
     *
     * @return string
     */
    public function getEnemyContactName(): string
    {
        return $this->enemyContactName;
    }

    /**
     * Sets the enemy Contact Name.
     *
     * @param string $enemyContactName
     * @return $this
     */
    public function setEnemyContactName(string $enemyContactName): Enemy
    {
        $this->enemyContactName = $enemyContactName;

        return $this;
    }

    /**
     * Gets the enemy Contact Email.
     *
     * @return string
     */
    public function getEnemyContactEmail(): string
    {
        return $this->enemyContactEmail;
    }

    /**
     * Sets the enemy Contact Email.
     *
     * @param string $enemyContactEmail
     * @return $this
     */
    public function setEnemyContactEmail(string $enemyContactEmail): Enemy
    {
        $this->enemyContactEmail = $enemyContactEmail;

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
                'name' => $this->getEnemyName(),
                'tag' => $this->getEnemyTag(),
                'image' => $this->getEnemyImage(),
                'homepage' => $this->getEnemyHomepage(),
                'contact_name' => $this->getEnemyContactName(),
                'contact_email' => $this->getEnemyContactEmail(),
            ]
        );
    }
}
