<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Models;

defined('ACCESS') or die('no direct access');

/**
 * The comment model class.
 *
 * @package ilch
 */
class Comment extends \Ilch\Model
{
    /**
     * @var integer
     */
    protected $id;
	
    /**
     * @var integer
     */
    protected $fkid;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var integer
     */
    protected $userId;
	
    /**
     * @var DateTime
     */
    protected $dateCreated;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param integer $id
     * @return this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }
	
    /**
     * @return integer
     */
    public function getName()
    {
        return $this->cc_strname;
    }

    /**
     * @param integer $name
     * @return this
     */
    public function setName($name)
    {
        $this->cc_strname = (int)$name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->cc_strcode;
    }

    /**
     * @param string $code
     * @return this
     */
    public function setCode($code)
    {
        $this->cc_strcode = (string)$code;

        return $this;
    }

    /**
     * @return string
     */
    public function getActive()
    {
        return $this->cc_bolactive;
    }

    /**
     * @param string $active
     * @return this
     */
    public function setActive($active)
    {
        $this->cc_bolactive = (string)$active;

        return $this;
    }
}
