<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Models;

use Ilch\Model;

class Backup extends Model
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Sets the id of the box.
     *
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * Gets the backup name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the backup name.
     *
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Gets the backup date.
     **
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Sets the backup date.
     *
     * @param string $date
     */
    public function setDate(string $date)
    {
        $this->date = $date;
    }
}
