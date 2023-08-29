<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Rule\Mappers;

use Ilch\Pagination;
use Modules\Rule\Models\Rule as RuleModel;

class Rule extends \Ilch\Mapper
{
    /**
     * @var string
     */
    public $tablename = 'rules';

    /**
     * @var string
     */
    public $tablenameAccess = 'rules_access';

    /**
     * returns if the module is installed.
     *
     * @return bool
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename) && $this->db()->ifTableExists($this->tablenameAccess);
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param Pagination|null $pagination
     * @return RuleModel[]|null
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['position' => 'ASC'], ?Pagination $pagination = null): ?array
    {
        $access = '';
        if (isset($where['ra.group_id'])) {
            $access = $where['ra.group_id'];
            unset($where['ra.group_id']);
        }

        $select = $this->db()->select();
        $select->fields(['r.id', 'r.paragraph', 'r.title', 'r.text', 'r.position', 'r.parent_id', 'r.access_all'])
            ->from(['r' => $this->tablename])
            ->join(['p' => $this->tablename], ['r.parent_id = p.id'], 'LEFT', ['parent_title' => 'p.title'])
            ->join(['ra' => $this->tablenameAccess], 'r.id = ra.rule_id', 'LEFT', ['access' => 'GROUP_CONCAT(ra.group_id)'])
            ->where(array_merge($where, ($access ? [$select->orX(['ra.group_id' => $access, 'r.access_all' => '1'])] : [])))
            ->order($orderBy)
            ->group(['r.id']);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entriesArray = $result->fetchRows();
        if (empty($entriesArray)) {
            return null;
        }
        $entries = [];

        foreach ($entriesArray as $entry) {
            $entryModel = new RuleModel();
            $entryModel->setByArray($entry);

            $entries[] = $entryModel;
        }
        return $entries;
    }

    /**
     * Gets the Rule entries.
     *
     * @param array $where
     *  @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return RuleModel[]|null
     */
    public function getRules(array $where = [], $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getEntriesBy(array_merge($where, ($groupIds ? ['ra.group_id' => $groupIds] : [])));
    }

    /**
     * Gets rule.
     *
     * @param int $id
     * @return RuleModel|null
     */
    public function getRuleById(int $id): ?RuleModel
    {
        $entries = $this->getEntriesBy(['r.id' => $id], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Gets all Rules items by parent item id.
     * @param int $itemId
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return null|RuleModel[]
     */
    public function getRulesItemsByParent(int $itemId, $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getEntriesBy(array_merge(['r.parent_id' => $itemId], ($groupIds ? ['ra.group_id' => $groupIds] : [])));
    }

    /**
     * Sort rules.
     *
     * @param int $id
     * @param int $position
     * @return bool
     */
    public function sort(int $id, int $position): bool
    {
        return $this->db()->update($this->tablename)
            ->values(['position' => $position])
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Inserts or updates rule model.
     *
     * @param RuleModel $rule
     * @return int
     */
    public function save(RuleModel $rule): int
    {
        $fields = $rule->getArray(false);

        if ($rule->getId()) {
            $itemId = $rule->getId();
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $rule->getId()])
                ->execute();
        } else {
            if ($fields['parent_id'] == 0) {
                // New category. Add to the end (max+1 position)
                $lastPosition = $this->db()->select('MAX(`position`) as lastPosition')
                    ->from($this->tablename)
                    ->execute()
                    ->fetchAssoc();

                $fields['position'] = $lastPosition['lastPosition'] + 1;
            } else {
                // New rule. Add at the end of it's category.
                $lastPosition = $this->db()->select('position as lastPosition')
                    ->from($this->tablename)
                    ->where(['parent_id' => $fields['parent_id']])
                    ->order(['position' => 'DESC'])
                    ->limit(1)
                    ->execute()
                    ->fetchAssoc();

                $fields['position'] = $lastPosition['lastPosition'];
            }

            $itemId = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        $this->saveAccess($itemId, $rule->getAccess());

        return $itemId;
    }

    /**
     * Update the entries for which user groups are allowed to read a Cat.
     *
     * @param int $ruleId
     * @param string|array $access example: "1,2,3"
     * @param boolean $addAdmin
     */
    public function saveAccess(int $ruleId, $access, bool $addAdmin = true)
    {
        if (\is_string($access)) {
            $access = explode(',', $access);
        }

        // Delete possible old entries to later insert the new ones.
        $this->db()->delete($this->tablenameAccess)
            ->where(['rule_id' => $ruleId])
            ->execute();

        $sql = 'INSERT INTO [prefix]_' . $this->tablenameAccess . ' (rule_id, group_id) VALUES';
        $sqlWithValues = $sql;
        $rowCount = 0;
        $groupIds = [];
        if (!empty($access)) {
            if (!in_array('all', $access)) {
                $groupIds = $access;
            }
        }
        if ($addAdmin && !in_array('1', $groupIds)) {
            $groupIds[] = '1';
        }

        foreach ($groupIds as $groupId) {
            // There is a limit of 1000 rows per insert, but according to some benchmarks found online
            // the sweet spot seams to be around 25 rows per insert. So aim for that.
            if ($rowCount >= 25) {
                $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
                $this->db()->queryMulti($sqlWithValues);
                $rowCount = 0;
                $sqlWithValues = $sql;
            }

            $rowCount++;
            $sqlWithValues .= '(' . $ruleId . ',' . (int)$groupId . '),';
        }

        if ($sqlWithValues != $sql) {
            // Insert remaining rows.
            $sqlWithValues = rtrim($sqlWithValues, ',') . ';';
            $this->db()->queryMulti($sqlWithValues);
        }
    }

    /**
     * Deletes rule with given id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }
}
