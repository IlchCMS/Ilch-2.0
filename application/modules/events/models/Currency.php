<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Models;

class Currency extends \Ilch\Model
{
    /**
     * The id of the currency.
     *
     * @var integer
     */
    protected $id;

    /**
     * The name of the currency.
     *
     * @var string
     */
    protected $name;

    /**
     * Gets the id of the currency.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the currency.
     *
     * @param integer $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * Gets the name of the currency.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the currency.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = (string)$name;

        return $this;
    }
}
