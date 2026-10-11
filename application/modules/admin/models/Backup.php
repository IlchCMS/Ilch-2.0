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
     * The id of the backup.
     */
    protected ?int $id = null;

    /**
     * The name of the backup.
     */
    protected ?string $name = null;

    /**
     * The datetime of the backup.
     */
    protected ?string $date = null;

    /**
     * Gets the id of the backup.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the id of the backup.
     *
     * @param int $id
     */
    public function setId(int $id): Backup
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets the backup name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the backup name.
     *
     * @param string $name
     */
    public function setName(string $name): Backup
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the backup date.
     *
     * @return string|null
     */
    public function getDate(): ?string
    {
        return $this->date;
    }

    /**
     * Sets the backup date.
     *
     * @param string $date
     */
    public function setDate(string $date): Backup
    {
        $this->date = $date;

        return $this;
    }
}
