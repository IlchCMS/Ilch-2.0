<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Birthday\Mappers;

use Ilch\Date;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Models\User as UserModel;
use Ilch\Pagination;

class Birthday extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.7.2
     */
    public $tablename = 'users';

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return UserModel[]|null
     * @since 1.7.2
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['birthday' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $userMapper = new UserMapper();

        $select = $this->db()->select()->fields(['id', 'birthday'])
            ->from($this->tablename)
            ->where($where)
        ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $row) {
            $entrys[] = $userMapper->getUserById($row['id']);
        }

        return $entrys;
    }

    /**
     * @param int|null $limit
     * @return UserModel[]
     */
    public function getBirthdayUserList(?int $limit = null): array
    {
        $pagination = null;
        if ($limit) {
            $pagination = new Pagination();
            $pagination->setRowsPerPage($limit);
        }

        $select = $this->db()->select();
        $users = $this->getEntriesBy([$select->andX([
                new \Ilch\Database\Mysql\Expression\Comparison('DAY(' . $this->db()->quote('birthday') . ')', '=', 'DAY(CURDATE())'),
                new \Ilch\Database\Mysql\Expression\Comparison('MONTH(' . $this->db()->quote('birthday') . ')', '=', 'MONTH(CURDATE())')
            ])], ['birthday' => 'ASC'], $pagination);
        if (!$users) {
            return [];
        }
        return $users;
    }

    /**
     * Gets the Users by start and end.
     *
     * @param string $start
     * @param string $end
     * @return UserModel[]|null
     */
    public function getEntriesForJson(string $start, string $end): ?array
    {
        if ($start && $end) {
            $start = new Date($start);
            $end = new Date($end);

            $select = $this->db()->select();
            if ($start->format('z') <= $end->format('z')) {
                $condition = $select->andX([
                    new \Ilch\Database\Mysql\Expression\Comparison(
                        'DAYOFYEAR(' . $this->db()->quote('birthday') . ')',
                        '>=',
                        'DAYOFYEAR(' . $this->db()->escape($start->format('Y-m-d'), true) . ')'
                    ),
                    new \Ilch\Database\Mysql\Expression\Comparison(
                        'DAYOFYEAR(' . $this->db()->quote('birthday') . ')',
                        '<=',
                        'DAYOFYEAR(' . $this->db()->escape($end->format('Y-m-d'), true) . ')'
                    ),
                ]);
            } else {
                $condition = $select->orX([
                    new \Ilch\Database\Mysql\Expression\Comparison(
                        'DAYOFYEAR(' . $this->db()->quote('birthday') . ')',
                        '>=',
                        'DAYOFYEAR(' . $this->db()->escape($start->format('Y-m-d'), true) . ')'
                    ),
                    new \Ilch\Database\Mysql\Expression\Comparison(
                        'DAYOFYEAR(' . $this->db()->quote('birthday') . ')',
                        '<=',
                        'DAYOFYEAR(' . $this->db()->escape($end->format('Y-m-d'), true) . ')'
                    ),
                ]);
            }

            $users = $this->getEntriesBy([$condition], ['birthday' => 'ASC']);

            if (!$users) {
                return [];
            }
            return $users;
        } else {
            return null;
        }
    }
}
