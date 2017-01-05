<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Models;

class Backup extends \Ilch\Model
{
    /**
     * The backup of the backup.
     *
     * @var int
     */
    protected $id;

    /**
     * The name of the backup.
     *
     * @var string
     */
    protected $name;

    /**
     * The datetime of the backup.
     *
     * @var string
     */
    protected $date;

    /**
     * Gets the id of the box.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the id of the box.
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * Gets the backup name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the backup name.
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * Gets the backup date.
     **
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the backup date.
     *
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}
