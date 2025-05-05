<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Birthday\Mappers;

use Ilch\Database\Exception;
use Ilch\Mapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;

class Birthday extends Mapper
{
    /**
     * Get a list of users birthdays.
     *
     * @param int|null $limit
     * @return UserModel[]|null
     * @throws Exception
     */
    public function getBirthdayUserList(?int $limit = null): ?array
    {
        $userMapper = new UserMapper();

        $sql = 'SELECT `pfc`.`user_id`, `pfc`.`field_id`, `pfc`.`value`
                FROM `[prefix]_profile_content` AS `pfc`
                INNER JOIN `[prefix]_profile_fields` AS `pf` ON (`pfc`.`field_id` = `pf`.`id` AND `pf`.`key` = "birthday" AND `pf`.`core` = 1)
                WHERE DAY(`value`) = DAY(CURDATE()) AND MONTH(`value`) = MONTH(CURDATE())';

        if ($limit != '') {
            $sql .= ' LIMIT ' . (int)$limit;
        }
        $rows = $this->db()->queryArray($sql);

        if (empty($rows)) {
            return null;
        }

        $users = [];
        foreach ($rows as $row) {
            $users[] = $userMapper->getUserById($row['user_id']);
        }

        return $users;
    }

    /**
     * Gets the Users by start and end.
     *
     * @param string $start
     * @param string $end
     * @return UserModel[]|null
     * @throws Exception
     */
    public function getEntriesForJson(string $start, string $end): ?array
    {
        if ($start && $end) {

            $sql = 'SELECT *
                    FROM `[prefix]_users`
                    WHERE DayOfYear(`birthday`)
                        BETWEEN DayOfYear("' . $this->db()->escape($start) .'")
                        AND DayOfYear("' .  $this->db()->escape($end) . '")';
        } else {
            return null;
        }

        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
        }

        $entry = [];
        foreach ($entryArray as $entries) {
            $entryModel = new UserModel();
            $entryModel->setId($entries['id']);
            $entryModel->setName($entries['name']);
            $entryModel->setBirthday($entries['birthday']);
            $entry[] = $entryModel;
        }

        return $entry;
    }
}
