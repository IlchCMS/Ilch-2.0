<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

class CookieStolen extends \Ilch\Mapper
{
    /**
     * Checks whether the database contains a cookie stolen entry for the given user.
     *
     * @param int $userid
     * @return bool
     */
    public function containsCookieStolen(int $userid): bool
    {
        $select = $this->db()->select('*');
        return (bool) $select->from('cookie_stolen')
            ->where(['userid' => $userid])
            ->execute()
            ->getNumRows();
    }

    /**
     * Adds a cookie stolen entry for the given user.
     *
     * Does nothing if an entry for that user already exists.
     *
     * @param int $userid
     * @return int 0 if an entry already exists, number of inserted rows otherwise
     */
    public function addCookieStolen(int $userid): int
    {
        if ($this->containsCookieStolen($userid)) {
            return 0;
        }
        $insert = $this->db()->insert();
        return $insert->into('cookie_stolen')
            ->values(['userid' => $userid])
            ->execute();
    }

    /**
     * Deletes the cookie stolen entry of the given user from the database.
     *
     * @param int $userid
     * @return int Number of deleted rows
     */
    public function deleteCookieStolen(int $userid): int
    {
        $delete = $this->db()->delete();
        return $delete->from('cookie_stolen')
            ->where(['userid' => $userid])
            ->execute();
    }
}
